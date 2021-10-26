<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;

        $sets = $user->username != 'administrator' ? $current_facility->sets()->orderBy('created_at', 'desc')->get() : Set::orderBy('created_at', 'desc')->get();

        return view('export', compact('sets'));
    }
}
