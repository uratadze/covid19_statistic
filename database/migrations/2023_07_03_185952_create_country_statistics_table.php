<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained();
            $table->unsignedInteger('confirmed');
            $table->unsignedInteger('recovered');
            $table->unsignedInteger('critical');
            $table->unsignedInteger('deaths');
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
        Schema::dropIfExists('country_statistics');
    }
}
