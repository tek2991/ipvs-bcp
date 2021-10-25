<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ArticleTransactionType;

class PendingArticleController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $article_statuses = ArticleTransactionType::whereIn('name', ['OP'])->get()->modelKeys();
        $articles = $active_set->articles()->whereIn('article_transaction_type_id', $article_statuses)->paginate();

        return view('pendingArticles', compact('active_set', 'articles'));
    }
}
