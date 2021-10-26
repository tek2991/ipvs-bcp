<?php

namespace App\Http\Controllers;

use App\Models\Set;
use App\Exports\BagExport;
use Illuminate\Http\Request;
use App\Exports\ArticleExport;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ArticleTransactionType;

class ExportController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;

        $sets = $user->username != 'administrator' ? $current_facility->sets()->orderBy('created_at', 'desc')->get() : Set::orderBy('created_at', 'desc')->get();

        return view('export', compact('sets'));
    }

    public function export(Request $request){
        $this->validate($request, [
            'set_id' => 'nullable|integer|exists:sets,id',
            'report_type' => 'nullable|string|in:bag_receive,bag_dispatch,article_open,article_close'
        ]);

        $set = Set::find($request->set_id);

        if(in_array($request->report_type, ['bag_receive','bag_dispatch'])){
            $bag_status_names = $request->report_type == 'bag_receive' ? ['RD', 'OP'] : ['CL', 'DI'];
            $bag_status_ids = BagTransactionType::whereIn('name', $bag_status_names)->get()->modelKeys();
            $bags = $set->bags()->whereIn('bag_transaction_type_id', $bag_status_ids)->with('bagType', 'bagTransactionType')->get();
            
            $status = $request->report_type == 'bag_receive' ? 'RD' : 'DI';
            $name = $set->facility->facility_code.'_'.'GEN1'.'_'.date_format($set->updated_at, "YmdHi").'.xlsx';
            // dd($name, $bags);
            return Excel::download(new BagExport($bags, $status), $name);
        }else{
            $article_status_names = $request->report_type == 'article_open' ? ['OP', 'CL'] : ['CL'];
            $article_status_ids = ArticleTransactionType::whereIn('name', $article_status_names)->get()->modelKeys();
            $articles = $set->articles()->whereIn('article_transaction_type_id', $article_status_ids)->with('articleType', 'articleTransactionType')->get();

            $name = $set->facility->facility_code.'_'.'GEN1'.'_'.date_format($set->updated_at, "YmdHi").'.xlsx';
            dd($name, $articles);
            return Excel::download(new ArticleExport($articles), $name);
        }
    }
}
