<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ArticleTransactionTypeSeeder::class,
            ArticleTypeSeeder::class,
            BagTransactionTypeSeeder::class,
            BagTypeSeeder::class,
            FacilityTypeSeeder::class,
            FacilitySeeder::class,
            CadreSeeder::class,
            UserSeeder::class,
            // MapAllFacilitiesSeeder::class,
        ]);
    }
}
