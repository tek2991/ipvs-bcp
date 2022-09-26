<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use App\Models\Export;
use App\Models\Article;
use App\Models\BagType;
use App\Models\Facility;
use App\Models\ArticleType;
use Illuminate\Http\Request;
use App\Rules\BagClosingRule;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Rules\ArticleNumberRule;
use App\Rules\ArticleClosingRule;
use App\Models\BagTransactionType;
use App\Exports\ArticleCloseExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ArticleTransactionType;

class BagCloseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $facility_ids = $current_facility->mappedFacilities()->get('mapped_facility_id')->pluck('mapped_facility_id')->toArray();
        $facilities = Facility::whereIn('id', $facility_ids)->orderBy('name')->get();
        $bag_types = BagType::get();

        $bag_statuses = BagTransactionType::whereIn('name', ['CL_SCAN'])->get()->modelKeys();
        $close_bags = Bag::where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        return view('bagClose', compact('active_set', 'facilities', 'bag_types', 'close_bags'));
    }

    public function bagScan(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $article_types = ArticleType::get();

        $bag_transaction_type_id = BagTransactionType::where('name', 'CL_SCAN')->get()->first()->id;

        $this->validate($request, [
            'to_facility_id' => 'bail|required|exists:facilities,id',
            'update_destination' => 'bail|boolean',
            'bag_type_id' => 'bail|required|exists:bag_types,id',
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[sS]{1}[0-9]{10}$^',
                Rule::unique('bags')->where(function ($query) use ($active_set, $bag_transaction_type_id) {
                    return $query->where('set_id', $active_set->id)->whereNotIn('bag_transaction_type_id', [$bag_transaction_type_id]);
                }),
                // new BagClosingRule($active_set_id),
            ],
        ]);

        if (!Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->where('bag_transaction_type_id', $bag_transaction_type_id)->exists()) {
            Bag::create([
                'bag_no' => strtoupper($request->bag_no),
                'bag_type_id' => $request->bag_type_id,
                'from_facility_id' => $current_facility->id,
                'to_facility_id' => $request->to_facility_id,
                'bag_transaction_type_id' => $bag_transaction_type_id,
                'set_id' => $active_set->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        } else if ($request->update_destination) {
            $bag_to_update = Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->where('bag_transaction_type_id', $bag_transaction_type_id)->firstOrFail();

            $bag_to_update->update([
                'to_facility_id' => $request->to_facility_id,
                'bag_type_id' => $request->bag_type_id,
            ]);
        }

        $bag = Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->where('bag_transaction_type_id', $bag_transaction_type_id)->firstOrFail();

        if ($bag->bag_transaction_type_id == $bag_transaction_type_id && $bag->set_id != $active_set->id) {
            return redirect()
                ->back()
                ->with('error', 'Bag is in another set: ' . $bag->set->name);
        }
        
        // Check if bag is opened by another user.
        if($bag->bag_transaction_type_id == $bag_transaction_type_id && $bag->updated_by != $user->id) {
            return redirect()
                ->back()
                ->with('error', 'Bag is being closed by another user: '. $bag->updator->name);
        }

        $articles = $bag->articles()->with('articleType')->orderBy('created_at', 'desc')->paginate();

        return view('bagCloseArticleScan', compact('active_set', 'request', 'bag', 'articles', 'article_types'));
    }

    public function articleScan(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $this->validate($request, [
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[sS]{1}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set) {
                    $bag_statuses = BagTransactionType::whereIn('name', ['CL_SCAN'])->get()->modelKeys();
                    return $query->where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ],

            'bag_id' => 'bail|required|exists:bags,id',

            'article_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[0-9]{9}[a-zA-Z]{2}$^',
                new ArticleNumberRule,
                new ArticleClosingRule($active_set, $request->confirm_force ? true : false),
            ],
            'to_facility_id' => 'bail|required|integer|exists:facilities,id',

            'confirm_force' => 'bail|boolean',

            'article_type_id' => [
                'bail', 'integer', 'exists:article_types,id', 'exists:article_types,id',
                Rule::requiredIf($request->confirm_force == 1 || $request->confirm_force == 'true')
            ],
        ]);

        $article_transaction_type_id = ArticleTransactionType::where('name', 'CL_SCAN')->get()->first()->id;
        $article_statuses = ArticleTransactionType::whereIn('name', ['OP'])->get()->modelKeys();
        // Get the article
        $article = Article::where('article_no', $request->article_no)->where('set_id', $active_set->id)->whereIn('article_transaction_type_id', $article_statuses)->first();

        // If confirm_force is true check if the article does not exist
        if ($request->confirm_force && !$article) {
            // Create the article
            $article = Article::create([
                'article_no' => strtoupper($request->article_no),
                'from_facility_id' => $current_facility->id,
                'to_facility_id' => $request->to_facility_id,
                'article_transaction_type_id' =>  $article_transaction_type_id,
                'bag_id' => $request->bag_id,
                'closing_bag_id' => $request->bag_id,
                'set_id' => $active_set->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'article_type_id' => $request->article_type_id,
                'is_insured' => false,
                'opening_bag_id' => null,
            ]);

        }else{
            // Update the article
            $article->update([
                'article_no' => strtoupper($request->article_no),
                'from_facility_id' => $current_facility->id,
                'to_facility_id' => $request->to_facility_id,
                'article_transaction_type_id' =>  $article_transaction_type_id,
                'bag_id' => $request->bag_id,
                'closing_bag_id' => $request->bag_id,
                'set_id' => $active_set->id,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        }

        return redirect()
            ->back()
            ->withInput()->with('scroll', true);
    }

    public function articleDeleteScan(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $article_status = ArticleTransactionType::whereIn('name', ['CL_SCAN'])->get()->modelKeys();

        $this->validate($request, [
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[sS]{1}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set) {
                    $bag_statuses = BagTransactionType::whereIn('name', ['CL_SCAN'])->get()->modelKeys();
                    return $query->where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ],

            'bag_id' => 'bail|required|exists:bags,id',

            'article_no_for_delete' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[0-9]{9}[a-zA-Z]{2}$^',
                Rule::exists('articles', 'article_no')->where(function ($query) use ($request, $article_status) {
                    return $query->where('bag_id', $request->bag_id)->whereIn('article_transaction_type_id', $article_status);
                })
            ],
        ]);

        $article = Article::where('article_no', $request->article_no_for_delete)->where('bag_id', $request->bag_id)->whereIn('article_transaction_type_id', $article_status)->first();

        $opening_bag = $article->openingBag;

        $article_transaction_type_id = ArticleTransactionType::where('name', 'OP')->get()->first()->id;

        $article->update([
            'from_facility_id' => $opening_bag->fromFacility->id,
            'to_facility_id' => $current_facility->id,
            'article_transaction_type_id' =>  $article_transaction_type_id,
            'bag_id' => $opening_bag->id,
            'closing_bag_id' => null,
            'set_id' => $active_set->id,
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->back()
            ->withInput()
            ->with('scroll', true)
            ->with('success', 'Article removed from list: ' . $request->article_no_for_delete);
    }

    public function save(Bag $bag, Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $this->validate($request, [
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[sS]{1}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set) {
                    $bag_statuses = BagTransactionType::whereIn('name', ['CL_SCAN'])->get()->modelKeys();
                    return $query->where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ],
        ]);

        $bag_transaction_type_id = BagTransactionType::where('name', 'CL')->get()->first()->id;
        $bag->update([
            'bag_transaction_type_id' => $bag_transaction_type_id,
        ]);

        $article_transaction_type_id = ArticleTransactionType::where('name', 'CL')->get()->first()->id;
        $bag->articles()->update([
            'article_transaction_type_id' => $article_transaction_type_id,
        ]);

        // declare variables for excel export

        $current_set = $current_facility->sets()->where('is_active', true)->first();
        $time = Carbon::now();
        $file_name = $current_set->facility->facility_code . '_' . $current_set->setType->name . '_' . date_format($time, "YmdHis") . '.xlsx';
        $articles = $bag->articles->load('openingBag.fromFacility', 'articleType', 'articleTransactionType');
        $type = 'CL';

        // Save excel file
        $set_date_and_type = $current_set->created_at->format('Ymd') . '_' . $current_set->setType->name;
        $user_name = $user->name;

        // Path to save excel file
        $path = 'exports/' . $set_date_and_type . '/' . $user_name . '/' . $type . '/';
        Excel::store(new ArticleCloseExport($articles, $type), $path . $file_name);

        // Record export in database
        Export::create([
            'user_id' => $user->id,
            'set_id' => $current_set->id,
            'bag_id' => $bag->id,
            'type' => $type,
            'file_name' => $file_name,
            'file_path' => $path . $file_name,
        ]);


        return redirect()->route('bag-close.index')->with('manifest', $bag);
    }
}
