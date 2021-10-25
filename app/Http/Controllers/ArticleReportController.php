<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleReportController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $this->validate($request, [
            'article_no' => 'nullable|exists:articles'
        ]);

        $article_rows = Article::where('article_no', $request->article_no)->orderBy('updated_at')->paginate();

        return view('articleReport', compact('active_set', 'request', 'article_rows'));
    }
}
