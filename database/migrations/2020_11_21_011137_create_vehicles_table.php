<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->integer('derivative_id');
            $table->foreign('derivative_id')->references('id')->on('derivatives');
            $table->integer('colour_id');
            $table->foreign('colour_id')->references('id')->on('colours');
            $table->integer('vehicle_type_id');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types');
            $table->string('registration')->length(7);
            // $table->decimal('price_ex_vat', 19, 4);
            // $table->decimal('vat', 19 ,4);
            $table->decimal('price_inc_vat', 19 ,4);
            $table->integer('mileage')->nullable();
            $table->datetime('date_on_forecourt')->nullable();
            $table->string('images');
            // $table->boolean('active');
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
        Schema::dropIfExists('vehicles');
    }
}
