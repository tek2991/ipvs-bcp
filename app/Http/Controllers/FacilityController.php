<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Facility;
use App\Models\FacilityType;
use Illuminate\Http\Request;
use App\Models\ReportingCircle;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('facilityIndex');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('facilityCreate', [
            'facility_types' => FacilityType::all(),
            'districts' => District::all(),
            'reporting_circles' => ReportingCircle::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'facility_code' => 'required|unique:facilities',
            'name' => 'required',
            'pincode' => 'required|min:6|max:6',
            'district_id' => 'required|exists:districts,id',
            'reporting_circle_id' => 'required|exists:reporting_circles,id',
            'facility_type_id' => 'required|exists:facility_types,id',
            'is_active' => 'required|boolean',
        ]);

        Facility::create($request->only([
            'facility_code',
            'name',
            'pincode',
            'district_id',
            'reporting_circle_id',
            'facility_type_id',
            'is_active',
        ]));

        return redirect()->route('facility.index')->with('success', 'Facility created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function show(Facility $facility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function edit(Facility $facility)
    {
        return view('facilityEdit', [
            'facility' => $facility,
            'facility_types' => FacilityType::all(),
            'districts' => District::all(),
            'reporting_circles' => ReportingCircle::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Facility $facility)
    {
        $this->validate($request, [
            'facility_code' => 'required|unique:facilities,facility_code,' . $facility->id,
            'name' => 'required',
            'pincode' => 'required|min:6|max:6',
            'district_id' => 'required|exists:districts,id',
            'reporting_circle_id' => 'required|exists:reporting_circles,id',
            'facility_type_id' => 'required|exists:facility_types,id',
            'is_active' => 'required|boolean',
        ]);

        $facility->update($request->only([
            'facility_code',
            'name',
            'pincode',
            'district_id',
            'reporting_circle_id',
            'facility_type_id',
            'is_active',
        ]));

        return redirect()->route('facility.index')->with('success', 'Facility updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function destroy(Facility $facility)
    {
        //
    }
}
