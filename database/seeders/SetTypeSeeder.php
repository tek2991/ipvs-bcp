<?php

namespace Database\Seeders;

use App\Models\SetType;
use Illuminate\Database\Seeder;

class SetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $set_types = [
            'GEN1',
            'GEN2',
            'GEN3',
            'GEN4',
            'GEN5',
        ];

        foreach ($set_types as $set_type) {
            SetType::create([
                'name' => $set_type,
            ]);
        }
    }
}
