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
        $selected_facility = Facility::find($request->facility_id);
        $facility_ids = $selected_facility->mappedFacilities()->get()->modelKeys();
        $facilities = Facility::whereIn('id', $facility_ids)->orderBy('pincode')->with('district', 'reportingCircle')->paginate();

        return view('facilityMappingShow', compact('facilities'));
    }
}
