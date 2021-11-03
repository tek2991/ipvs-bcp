<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ArticleTransactionType;

class InsuredReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $sets = $current_facility->sets()->orderBy('created_at', 'desc')->get();



        return view('insuredReport', compact('sets'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;

        $this->validate($request, [
            'set_id' => 'nullable|integer|exists:sets,id',
            'article_report_type' => 'nullable|string|in:open(Insured),close(Insured)'
        ]);
        $article_statuses = $request->article_report_type == 'open(Insured)' ? ['OP', 'CL_SCAN', 'CL'] : ['CL'];
        $article_status_ids = ArticleTransactionType::whereIn('name', $article_statuses)->get()->modelKeys();
        $set = Set::find($request->set_id);

        if ($request->article_report_type == 'open(Insured)') {
            $articles = $set->articles()->where('is_insured', true)->whereIn('article_transaction_type_id', $article_status_ids)->get();
            return view('pdf.ArticleOpenReport', compact('articles', 'set', 'request', 'user', 'current_facility'));

            // $pdf = PDF::loadView('pdf.ArticleOpenReport', compact('articles', 'set', 'request', 'user', 'current_facility'));
            // return $pdf->download('article_open_report_' . $set->id . '.pdf');
        } else {
            $articles = $set->articles()->where('is_insured', true)->whereIn('article_transaction_type_id', $article_status_ids)->get();
            return view('pdf.ArticleCloseReport', compact('articles', 'set', 'request', 'user', 'current_facility'));

            // $pdf = PDF::loadView('pdf.ArticleCloseReport', compact('articles', 'set', 'request', 'user', 'current_facility'));
            // return $pdf->download('article_close_report_' . $set->id . '.pdf');
        }
    }
}
