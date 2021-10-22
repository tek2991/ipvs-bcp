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
        $facilities = Facility::get();
        $bag_types = BagType::get();

        $bag_statuses = BagTransactionType::whereIn('name', ['CL_SCAN'])->get()->modelKeys();
        $close_bags = Bag::where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        return view('bagClose', compact('active_set', 'facilities', 'bag_types', 'close_bags'));
    }

    public function bagScan(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $article_types = ArticleType::get();

        $bag_transaction_type_id = BagTransactionType::where('name', 'CL_SCAN')->get()->first()->id;

        $this->validate($request, [
            'to_facility_id' => 'bail|required|exists:facilities,id',
            'bag_type_id' => 'bail|required|exists:bag_types,id',
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[sS]{1}[0-9]{10}$^',
                // Rule::unique('bags')->where(function ($query) use ($active_set_id) {
                //     return $query->where('set_id', $active_set_id);
                // }),
                // new BagClosingRule($active_set_id),
            ],
        ]);

        if(!Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->where('bag_transaction_type_id', $bag_transaction_type_id)->exists()){
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
        }

        $bag = Bag::where('bag_no', $request->bag_no)->where('set_id', $active_set->id)->where('bag_transaction_type_id', $bag_transaction_type_id)->firstOrFail();
        $articles = $bag->articles()->orderBy('created_at', 'desc')->paginate();

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
            'set_id' => $active_set->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        return redirect()
            ->back()
            ->withInput();
    }
}
