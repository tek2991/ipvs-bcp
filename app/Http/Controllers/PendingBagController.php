<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;

class PendingBagController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $bag_statuses = BagTransactionType::whereIn('name', ['RD'])->get()->modelKeys();
        $bags = $active_set->bags()->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        return view('pendingBags', compact('active_set', 'bags'));
    }
}
