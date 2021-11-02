<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\ReportingCircle;
use Illuminate\Database\Seeder;

class AddFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $facilities = [
            ['id' => 'HO25204200000', 'name' => 'Tura H.O', 'district' => 'West Garo Hills', 'pincode' => '794001', 'circle' => 'North East', 'type' => 'HO'],
            ['id' => 'PO25204215000', 'name' => 'Tikrikilla S.O', 'district' => 'West Garo Hills', 'pincode' => '794109', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204216000', 'name' => 'Williamnagar S.O', 'district' => 'East Garo Hills', 'pincode' => '794111', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204217000', 'name' => 'Dadengiri S.O', 'district' => 'Tura', 'pincode' => '794003', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204201000', 'name' => 'Ampati S.O', 'district' => 'West Garo Hills', 'pincode' => '794115', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204202000', 'name' => 'Araimile S.O', 'district' => 'West Garo Hills', 'pincode' => '794101', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204203000', 'name' => 'Baghmara S.O', 'district' => 'South Garo Hills', 'pincode' => '794102', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204204000', 'name' => 'Barengapara S.O', 'district' => 'West Garo Hills', 'pincode' => '794103', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204205000', 'name' => 'Dobasipara S.O', 'district' => 'West Garo Hills', 'pincode' => '794005', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204206000', 'name' => 'Fulbari S.O (West Garo Hills)', 'district' => 'West Garo Hills', 'pincode' => '794104', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204207000', 'name' => 'Garobadha S.O', 'district' => 'West Garo Hills', 'pincode' => '794105', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204208000', 'name' => 'Lower Chandmari S.O', 'district' => 'West Garo Hills', 'pincode' => '794002', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204209000', 'name' => 'Mahendraganj S.O (West Garo Hills)', 'district' => 'West Garo Hills', 'pincode' => '794106', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204210000', 'name' => 'Mendipathar S.O', 'district' => 'West Garo Hills', 'pincode' => '794112', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204211000', 'name' => 'Nangwalbibra S.O', 'district' => 'West Garo Hills', 'pincode' => '794107', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204212000', 'name' => 'Resubelpara S.O', 'district' => 'West Garo Hills', 'pincode' => '794108', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204213000', 'name' => 'Rongjeng S.O', 'district' => 'East Garo Hills', 'pincode' => '794110', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'PO25204214000', 'name' => 'Rongra S.O', 'district' => 'South Garo Hills', 'pincode' => '794114', 'circle' => 'North East', 'type' => 'PO'],
            ['id' => 'HO12104100000', 'name' => 'Diphu H.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782460', 'circle' => 'Assam', 'type' => 'HO'],
            ['id' => 'PO12104101000', 'name' => 'Baithalangsho S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782450', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104102000', 'name' => 'Bakalighat S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782482', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104103000', 'name' => 'Bokajan C F S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782490', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104104000', 'name' => 'Bokajan S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782480', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104106000', 'name' => 'Diphu Govt.College S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782462', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104107000', 'name' => 'Dokmaka S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782441', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104108000', 'name' => 'Donkamokam S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782485', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104109000', 'name' => 'Hamren S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782486', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104112000', 'name' => 'Howraghat S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782481', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104115000', 'name' => 'Kheroni S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782448', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'PO12104118000', 'name' => 'Manja S.O', 'district' => 'KARBI ANGLONG', 'pincode' => '782461', 'circle' => 'Assam', 'type' => 'PO'],
            ['id' => 'HO25202100000', 'name' => 'Itanagar H.O', 'district' => 'Papum Pare', 'pincode' => '791111', 'circle' => 'North East', 'type' => 'HO']
        ];

        foreach ($facilities as $facility) {
            $facility_type = FacilityType::where('name', $facility['type'])->first();
            $district = District::where('name', 'ilike', $facility['district'])->first();
            $circle = ReportingCircle::where('name', $facility['circle'])->first();

            if (Facility::where('facility_code', $facility['id'])->exists()) {
                continue;
            }

            Facility::create([
                'facility_code' => $facility['id'],
                'name' => $facility['name'],
                'pincode' => $facility['pincode'],
                'facility_type_id' => $facility_type->id,
                'district_id' => $district->id,
                'reporting_circle_id' => $circle->id,
            ]);
        }
    }
}
