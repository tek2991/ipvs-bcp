<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Bag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;
use App\Models\ArticleTransactionType;
use App\Models\ArticleType;
use App\Rules\ArticleNumberRule;
use App\Rules\ArticleOpeningRule;

class BagOpenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $bag_statuses = BagTransactionType::whereIn('name', ['OP_SCAN'])->get()->modelKeys();

        $open_bags = Bag::where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        return view('bagOpenBagScan', compact('active_set', 'open_bags'));
    }

    public function bagScan(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $article_types = ArticleType::get();

        $bag_statuses = BagTransactionType::whereIn('name', ['RD', 'OP_SCAN'])->get()->modelKeys();

        $this->validate($request, [
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set, $bag_statuses) {
                    return $query->where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ],
        ]);

        $bag_transaction_type_id = BagTransactionType::where('name', 'OP_SCAN')->get()->firstOrFail()->id;

        $bag = Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses)->firstOrFail();

        if ($bag->bag_transaction_type_id = $bag_transaction_type_id && $bag->updated_by != $user->id) {
            return redirect()
                ->back()
                ->with('error', 'Bag is scaned by another user: ' . $bag->updator->name);
        }

        $bag->update([
            'bag_transaction_type_id' => $bag_transaction_type_id,
            'updated_by' => $user->id,
        ]);

        $articles = $bag->articles()->orderBy('created_at', 'desc')->paginate();

        return view('bagOpenArticleScan', compact('active_set', 'request', 'article_types', 'bag', 'articles'));
    }

    public function articleScan(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $this->validate($request, [
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set) {
                    $bag_statuses = BagTransactionType::whereIn('name', ['RD', 'OP_SCAN'])->get()->modelKeys();
                    return $query->where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ],

            'bag_id' => 'bail|required|exists:bags,id',

            'article_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[0-9]{9}[a-zA-Z]{2}$^',
                Rule::unique('articles')->where(function ($query) use ($active_set) {
                    return $query->where('set_id', $active_set->id);
                }),

                new ArticleNumberRule,

                new ArticleOpeningRule($request->bag_no, $current_facility, $active_set),
            ],

            'article_type_id' => 'bail|required|integer|exists:article_types,id',
            'is_insured' => 'bail|required|boolean',
            'from_facility_id' => 'bail|required|integer|exists:facilities,id'
        ]);

        $article_transaction_type_id = ArticleTransactionType::where('name', 'OP_SCAN')->get()->first()->id;

        Article::create([
            'article_no' => strtoupper($request->article_no),
            'article_type_id' => $request->article_type_id,
            'from_facility_id' => $request->from_facility_id,
            'to_facility_id' => $current_facility->id,
            'article_transaction_type_id' =>  $article_transaction_type_id,
            'bag_id' => $request->bag_id,
            'set_id' => $active_set->id,
            'is_insured' => $request->is_insured,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->back()
            ->withInput();
    }

    public function articleDeleteScan(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $article_status = ArticleTransactionType::whereIn('name', ['OP_SCAN'])->get()->modelKeys();

        $this->validate($request, [
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set) {
                    $bag_statuses = BagTransactionType::whereIn('name', ['RD', 'OP_SCAN'])->get()->modelKeys();
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

        $article->delete();

        return redirect()
        ->back()
        ->withInput();
    }
}
