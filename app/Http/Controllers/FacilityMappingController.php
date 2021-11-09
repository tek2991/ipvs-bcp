<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityMappingController extends Controller
{
    public function index(){
        $user = Auth::user();
    
        if($user->username != 'administrator'){
            return abort('403');
        }
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();

        return view('facilityMappingSelect', compact('facilities'));
    }

    public function list(Request $request){
        $user = Auth::user();
    
        if($user->username != 'administrator'){
            return abort('403');
        }

        $this->validate($request, [
            'facility_id' => 'bail|required|exists:facilities,id',
        ]);

        dd($request);
    }
}
