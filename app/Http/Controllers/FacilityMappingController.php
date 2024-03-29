<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Facility;
use App\Models\FacilityType;
use Illuminate\Http\Request;
use App\Models\FacilityMapping;
use App\Models\ReportingCircle;
use App\Imports\FacilitysImport;
use Illuminate\Support\Facades\Auth;

class FacilityMappingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->username != 'administrator') {
            return abort('403');
        }
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();

        return view('facilityMappingSelect', compact('facilities'));
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        if ($user->username != 'administrator') {
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


        $facility_ids = $selected_facility->mappedFacilities()->get('mapped_facility_id')->pluck('mapped_facility_id')->toArray();
        $facilities = Facility::whereIn('id', $facility_ids)->where('reporting_circle_id', 'like', $reporting_circle_id)->where('facility_type_id', 'like', $facility_type_id)->orderBy('name')->with('district', 'reportingCircle')->paginate();

        return view('facilityMappingShow', compact('facilities', 'reporting_circles', 'facility_types', 'active_facilities', 'request'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->username != 'administrator') {
            return abort('403');
        }
        $this->validate($request, [
            'base_facility_id' => 'bail|required|exists:facilities,id',
            'facility_code_for_mapping' => 'bail|required|exists:facilities,facility_code',
        ]);

        $facility_id_for_mapping = Facility::where('facility_code', $request->facility_code_for_mapping)->first()->id;

        FacilityMapping::updateOrCreate(
            [
                'base_facility_id' => $request->base_facility_id,
                'mapped_facility_id' => $facility_id_for_mapping
            ],
            ['mapped_facility_id' => $facility_id_for_mapping]
        );

        return redirect()
            ->back()
            ->withInput();
    }

    public function upload(Request $request)
    {
        // verify if the user is logged in as an administrator
        $user = Auth::user();
        if ($user->username != 'administrator') {
            return abort('403');
        }

        // validate the request
        $this->validate($request, [
            'file' => 'file|required|mimes:csv,xls,xlsx|max:2048',
            'base_facility_id' => 'bail|required|exists:facilities,id',
        ]);

        // declare the file name as a variable with the current time
        $file_name = time() . '_' . $request->file->getClientOriginalName();
        // get the file
        $file = $request->file('file');
        // store the file to the imports folder
        $file->storeAs('imports', $file_name);

        // Record the file name and path in the database
        $import = Import::create([
            'file_name' => $file_name,
            'file_path' => 'imports/' . $file_name,
            'is_imported' => false,
        ]);

        // try catch block
        try {
            // import the file in FacilitysImport class
            $facility_import = new FacilitysImport($request->base_facility_id);
            $facility_import->import($import->file_path);

            // update the import record to indicate that the file has been imported
            $import->update([
                'is_imported' => true,
            ]);

            // redirect to the list page
            return redirect()->back()->with('success', 'File uploaded successfully');
        } catch (\Exception $e) {
            // delete the file from the imports folder
            \Storage::delete('imports/' . $file_name);
            // delete the import record from the database
            $import->delete();
            // return the error message
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user->username != 'administrator') {
            return abort('403');
        }
        $this->validate($request, [
            'base_facility_id' => 'bail|required|exists:facilities,id',
            'facility_code_for_delete' => 'bail|required|exists:facilities,facility_code',
        ]);

        $facility_id_for_delete = Facility::where('facility_code', $request->facility_code_for_delete)->first()->id;

        FacilityMapping::where('base_facility_id', $request->base_facility_id)->where('mapped_facility_id', $facility_id_for_delete)->delete();

        return redirect()
            ->back()
            ->withInput();
    }
}
