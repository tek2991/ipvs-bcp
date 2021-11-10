<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\ReportingCircle;
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
            'reporting_circle_id' => 'nullable|integer|exists:reporting_circles,id',
            'facility_type_id' => 'nullable|integer|exists:facility_types,id',
        ]);

        $reporting_circles = ReportingCircle::get();
        $facility_types = FacilityType::get();

        $reporting_circle_id = $request->reporting_circle_id ?? '%%';
        $facility_type_id = $request->facility_type_id ?? '%%';

        $active_facilities = Facility::where('is_active', true)->orderBy('name')->get();
        $selected_facility = Facility::find($request->facility_id);
        $facility_ids = $selected_facility->mappedFacilities()->get()->modelKeys();
        $facilities = Facility::whereIn('id', $facility_ids)->where('reporting_circle_id', 'like', $reporting_circle_id)->where('facility_type_id', 'like', $facility_type_id)->orderBy('pincode')->with('district', 'reportingCircle')->paginate();

        return view('facilityMappingShow', compact('facilities', 'reporting_circles', 'facility_types', 'active_facilities', 'request'));
    }
}
