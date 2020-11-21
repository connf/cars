<?php

use App\Models\Vehicles;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVehiclesAddActiveFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $vehicles = Vehicles::all();

        foreach ($vehicles as $vehicle) {
            $vehicle->active = (
                $vehicle->date_on_forecourt > Carbon::now() 
                 || $vehicle->date_on_forecourt->year < 1
            ) ? false : true;

            $vehicle->update();
        }
dd('end');
        Schema::table('vehicles', function (Blueprint $table) {
            $table->boolean('active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
