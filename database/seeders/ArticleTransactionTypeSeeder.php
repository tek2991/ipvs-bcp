<?php

namespace Database\Seeders;

use App\Models\ArticleTransactionType;
use Illuminate\Database\Seeder;

class ArticleTransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $article_transaction_types = [
            'OP_SCAN' => 'Article Open Scan',  
            'OP' => 'Article Open',  
            'CL_SCAN' => 'Article Close Scan', 
            'CL' => 'Article Close',
            'TF' => 'Article Transfer', 
        ];

        foreach ($article_transaction_types as $key => $value) {
            ArticleTransactionType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
