<?php

namespace Database\Seeders;

use App\Models\ReportingCircle;
use Illuminate\Database\Seeder;

class ReportingCircleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $circles = [
            'Assam',
            'North East',
            'Telangana',
            'Andhra Pradesh',
            'Bihar',
            'Chhattisgarh',
            'Delhi',
            'Gujarat',
            'Haryana',
            'Himachal Pradesh',
            'Jammu Kashmir',
            'Jharkhand',
            'Karnataka',
            'Kerela',
            'Madhya Pradesh',
            'Maharashtra',
            'Odisha',
            'Punjab',
            'Rajasthan',
            'TamilNadu',
            'Uttar Pradesh',
            'Uttarkhand',
            'West Bengal',
        ];

        foreach ($circles as $circle) {
            ReportingCircle::create([
                'name' => $circle,
            ]);
        }
    }
}
