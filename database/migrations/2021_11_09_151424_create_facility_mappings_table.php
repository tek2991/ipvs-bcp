<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_facility_id')->constrained('facilities', 'id');
            $table->foreignId('mapped_facility_id')->constrained('facilities', 'id');
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
        Schema::dropIfExists('facility_mappings');
    }
}
