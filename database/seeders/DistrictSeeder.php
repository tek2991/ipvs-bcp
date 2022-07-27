<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = [
            'Kamrup ',
            'Baksa',
            'Barpeta',
            'Biswanath',
            'Bongaigaon',
            'Cachar',
            'Charaideo',
            'Chirang',
            'Darrang',
            'Dhemaji',
            'Dhubri',
            'Dibrugarh',
            'Dima Hasao (North Cachar Hills)',
            'Goalpara',
            'Golaghat',
            'Hailakandi',
            'Hojai',
            'Jorhat',
            'Kamrup Rural',
            'Kamrup Metropolitan',
            'Karbi Anglong',
            'Karimganj',
            'Kokrajhar',
            'Lakhimpur',
            'Majuli',
            'Morigaon',
            'Nagaon',
            'Nalbari',
            'Sivasagar',
            'Sonitpur',
            'South Salamara-Mankachar',
            'Tinsukia',
            'Udalguri',
            'West Karbi Anglong',
        ];

        foreach ($districts as $district) {
            District::create([
                'name' => $district,
            ]);
        }
    }
}
