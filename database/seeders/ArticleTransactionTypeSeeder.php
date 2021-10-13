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
            '' => ''  
        ];

        foreach ($article_transaction_types as $key => $value) {
            ArticleTransactionType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
