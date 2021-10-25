<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BagReportController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $sets = $current_facility->sets;

        $this->validate($request, [
            'set_id' => 'nullable|integer|exists:sets,id',
            'bag_report_type' => 'nullable|string|in:receive,close'
        ]);

        return view('bagReport', compact('sets', 'request'));
    }
}
