<?php

namespace App\Imports;


use App\Models\District;

use App\Models\Facility;
use App\Models\FacilityType;
use Illuminate\Support\Carbon;
use App\Models\ReportingCircle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class FacilitysImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public static $base_facility_id = null;

    public function __construct($base_facility_id)
    {
        self::$base_facility_id = $base_facility_id;
    }

    public function rules(): array
    {
        return [
            '*.facility_code' => 'required|alpha_num',
            '*.name' => 'required|string',
            '*.pincode' => 'required|numeric|digits:6',
            '*.facility_type' => 'required|string',
            '*.reporting_circle' => 'required|string',
            '*.district' => 'required|string',
        ];
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            // Check if facility_type exists
            $facility_type = $row['facility_type'];
            $facility_type = FacilityType::where('name', $facility_type)->first();
            if(!$facility_type){
                $facility_type = FacilityType::create([
                    'name' => $row['facility_type'],
                    'description' => $row['facility_type'],
                ]);
            }

            // Check if reporting_circle exists
            $reporting_circle = $row['reporting_circle'];
            $reporting_circle = ReportingCircle::where('name', $reporting_circle)->first();
            if(!$reporting_circle){
                $reporting_circle = ReportingCircle::create([
                    'name' => $row['reporting_circle'],
                ]);
            }

            // Check if district exists
            $district = $row['district'];
            $district = District::where('name', $district)->first();
            if(!$district){
                $district = District::create([
                    'name' => $row['district'],
                ]);
            }

            // Check if facility code already exists
            $facility_code = $row['facility_code'];
            $facility = Facility::where('facility_code', $facility_code)->first();

            // If facility does not exist, create it
            if(!$facility){
                $facility = Facility::create([
                    'facility_code' => $row['facility_code'],
                    'name' => $row['name'],
                    'pincode' => $row['pincode'],
                    'facility_type_id' => $facility_type->id,
                    'reporting_circle_id' => $reporting_circle->id,
                    'district_id' => $district->id,
                    'is_active' => False,
                ]);
            }else{
                // If facility exists, update it
                $facility->update([
                    'name' => $row['name'],
                    'pincode' => $row['pincode'],
                    'facility_type_id' => $facility_type->id,
                    'reporting_circle_id' => $reporting_circle->id,
                    'district_id' => $district->id,
                    'is_active' => False,
                ]);
            }
        }
    }
}
