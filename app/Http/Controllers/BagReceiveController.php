<?php

namespace App\Http\Controllers;

use App\Models\BagType;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BagReceiveController extends Controller
{
    public function index(){
        
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $bags_received = $active_set->bags()->paginate();

        $facilities = Facility::get();
        $bag_types = BagType::get();

        return view('bagReceive', compact('bags_received', 'active_set', 'facilities', 'bag_types'));
    }
}
