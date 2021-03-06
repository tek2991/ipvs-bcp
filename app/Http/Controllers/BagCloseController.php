<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use App\Models\Article;
use App\Models\BagType;
use App\Models\Facility;
use App\Models\ArticleType;
use Illuminate\Http\Request;
use App\Rules\BagClosingRule;
use Illuminate\Validation\Rule;
use App\Rules\ArticleNumberRule;
use App\Rules\ArticleClosingRule;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;
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
        }else if($request->update_destination){
            $bag_to_update = Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->where('bag_transaction_type_id', $bag_transaction_type_id)->firstOrFail();

            $bag_to_update->update([
                'to_facility_id' => $request->to_facility_id,
                'bag_type_id' => $request->bag_type_id,
                'updated_by' => $user->id,
            ]);
        }
        
        $bag = Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->where('bag_transaction_type_id', $bag_transaction_type_id)->firstOrFail();
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
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[0-9]{9}[a-zA-Z]{2}$^', 'exists:articles',
                new ArticleNumberRule,
                new ArticleClosingRule($active_set),
            ],
            'to_facility_id' => 'bail|required|integer|exists:facilities,id'
        ]);

        $article_transaction_type_id = ArticleTransactionType::where('name', 'CL_SCAN')->get()->first()->id;

        $article_statuses = ArticleTransactionType::whereIn('name', ['OP'])->get()->modelKeys();

        $article = Article::where('article_no', $request->article_no)->where('set_id', $active_set->id)->whereIn('article_transaction_type_id', $article_statuses);

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

    public function save(Bag $bag, Request $request){
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

        return redirect()->route('bag-close.index')->with('manifest', $bag);
    }
}
