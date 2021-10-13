<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bags', function (Blueprint $table) {
            $table->id();
            $table->string('bag_no');
            $table->foreignId('bag_type_id')->constrained('bag_types', 'id');
            $table->foreignId('from_facility_id')->constrained('facilities', 'id');
            $table->foreignId('to_facility_id')->constrained('facilities', 'id');
            $table->foreignId('bag_transaction_type_id')->constrained('bag_transaction_types', 'id');
            $table->foreignId('set_id')->constrained('sets', 'id');
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
        Schema::dropIfExists('bags');
    }
}
