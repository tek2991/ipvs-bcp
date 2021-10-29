<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ArticleTransactionType;

class ArticleReportController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;
        $sets = $current_facility->sets()->orderBy('created_at', 'desc')->get();



        return view('articleReport', compact('sets'));
    }

    public function store(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;

        $this->validate($request, [
            'set_id' => 'nullable|integer|exists:sets,id',
            'article_report_type' => 'nullable|string|in:open,close'
        ]);
        $article_statuses = $request->article_report_type == 'open' ? ['OP', 'CL_SCAN', 'CL'] : ['CL'];
        $article_status_ids = ArticleTransactionType::whereIn('name', $article_statuses)->get()->modelKeys();
        $set = Set::find($request->set_id);

        if($request->article_report_type == 'open'){
            $articles = $set->articles()->whereIn('article_transaction_type_id', $article_status_ids)->get();
            return view('pdf.ArticleOpenReport', compact('articles', 'set', 'request', 'user', 'current_facility'));
        }else{
            $articles = $set->articles()->whereIn('article_transaction_type_id', $article_status_ids)->get();
            return view('pdf.ArticleCloseReport', compact('articles', 'set', 'request', 'user', 'current_facility'));
        }

        
        // $pdf = PDF::loadView('pdf.manifest', ['bag' => $bag,]);
        // return $pdf->download('manifest_' . $bag->bag_no . '.pdf');

        // return view('pdf.bagReport', compact('articles', 'set', 'request', 'user', 'current_facility'));
    }
}
