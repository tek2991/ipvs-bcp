<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;
use App\Models\ArticleTransactionType;
use App\Models\SetType;

class SetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currently_active = $user->facility->sets->where('is_active', true);
        $previously_active = $user->facility->sets->where('is_active', false);
        $set_types = SetType::all();

        $pending_arr = [];
        $status_arr = [];
        if (count($currently_active) > 0) {
            $active_set = $currently_active->firstOrfail();

            $bag_receive_status = BagTransactionType::where('name', 'RD')->first()->id;
            $bag_dispatch_scan_status = BagTransactionType::where('name', 'DI_SCAN')->first()->id;
            $bags_in_receive_status = $active_set->bags()->where('bag_transaction_type_id', $bag_receive_status)->get();
            $bags_in_dispatch_scan_status = $active_set->bags()->where('bag_transaction_type_id', $bag_dispatch_scan_status)->get();
            $article_open_scan_status = ArticleTransactionType::where('name', 'OP_SCAN')->first()->id;
            $article_open_status = ArticleTransactionType::where('name', 'OP')->first()->id;
            $article_close_scan_status = ArticleTransactionType::where('name', 'CL_SCAN')->first()->id;
            $articles_in_open_scan_status = $active_set->articles()->where('article_transaction_type_id', $article_open_scan_status)->get();
            $articles_in_open_status = $active_set->articles()->where('article_transaction_type_id', $article_open_status)->get();
            $articles_in_close_scan_status = $active_set->articles()->where('article_transaction_type_id', $article_close_scan_status)->get();
            $pending_arr = compact('bags_in_receive_status', 'bags_in_dispatch_scan_status', 'articles_in_open_scan_status', 'articles_in_open_status', 'articles_in_close_scan_status');

            $bag_receive_statuses = BagTransactionType::whereIn('name', ['RD', 'OP_SCAN', 'OP'])->get()->modelKeys();
            $bags_received = $active_set->bags()->whereIn('bag_transaction_type_id', $bag_receive_statuses)->get();
            $bag_close_statuses = BagTransactionType::whereIn('name', ['CL_SCAN', 'CL'])->get()->modelKeys();
            $bags_closed = $active_set->bags()->whereIn('bag_transaction_type_id', $bag_close_statuses)->get();
            $article_open_statuses = ArticleTransactionType::whereIn('name', ['OP', 'CL_SCAN', 'CL'])->get()->modelKeys();
            $articles_opened = $active_set->articles()->whereIn('article_transaction_type_id', $article_open_statuses)->get();
            $article_close_statuses = ArticleTransactionType::whereIn('name', ['CL'])->get()->modelKeys();
            $articles_closed = $active_set->articles()->whereIn('article_transaction_type_id', $article_close_statuses)->get();

            $status_arr = compact('bags_received', 'bags_closed', 'articles_opened', 'articles_closed');
        }

        return view('set', compact('currently_active', 'previously_active', 'pending_arr', 'status_arr', 'set_types'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = Set::where('facility_id', $current_facility->id)->where('is_active', true)->get();
        
        $this->validate($request, [
            'confirm' => 'required|boolean',
            'set_type_id' => 'required|exists:set_types,id',
        ]);

        if (count($active_set) > 0) {
            return redirect()
                ->back()
                ->with('error', 'Please close open set, started on: ' . $active_set->first()->created_at->toDayDateTimeString());
        }

        Set::create([
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'facility_id' => $current_facility->id,
            'is_active' => true,
            'set_type_id' => $request->set_type_id,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Set started');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->get();

        $this->validate($request, [
            'confirm' => 'required|boolean'
        ]);
        
        if (count($active_set) == 0) {
            return redirect()
                ->back()
                ->with('error', 'No active set');
        }

        $bag_statuses = BagTransactionType::whereIn('name', ['RD', 'DI_SCAN'])->first()->id;
        $article_statuses = ArticleTransactionType::whereIn('name', ['OP_SCAN', 'OP', 'CL_SCAN'])->first()->id;

        $pending_bags = $active_set->first()->bags()->where('bag_transaction_type_id', $bag_statuses)->get()->count();
        $pending_articles = $active_set->first()->articles()->where('article_transaction_type_id', $article_statuses)->get()->count();

        if ($pending_bags + $pending_articles > 0) {
            return redirect()
                ->back()
                ->with('error', 'Pending bags: ' . $pending_bags . ', Pending articles: '. $pending_articles);
        }

        $active_set->first()->update([
            'is_active' => false,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Set closed');
    }
}
