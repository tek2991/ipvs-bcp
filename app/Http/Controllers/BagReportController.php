<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BagReportController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;
        
        $sets = $current_facility->sets;
        return view('bagReport', compact('sets'));
    }
}
