<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityMappingController extends Controller
{
    public function index(){
        
        $user = Auth::user();
        $current_facility = $user->facility;

        $facilities = Facility::where('is_active', true)->orderBy('name')->get();

        return view('facilityMappingSelect', compact('facilities'));
    }
}
