<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('article_no');
            $table->foreignId('article_type_id')->constrained('article_types');
            $table->foreignId('from_facility_id')->constrained('facilities', 'id');
            $table->foreignId('to_facility_id')->constrained('facilities', 'id');
            $table->foreignId('article_transaction_type_id')->constrained('article_transaction_types');
            $table->foreignId('bag_id')->constrained('bags');
            $table->foreignId('set_id')->constrained('sets');
            $table->boolean('is_insured');
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->foreignId('updated_by')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
