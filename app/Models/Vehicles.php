<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;

    protected $dates = [
    	'date_on_forecourt'
    ];

    protected $fillable = [
        'colour_id',
        'derivative_id',
        'vehicle_type_id',
        'registration',
        'price_inc_vat',
        'mileage',
        'date_on_forecourt',
        'images'
    ];

    public function colour()
    {
    	return $this->hasOne(Colours::class);
    }

    public function type()
    {
    	return $this->hasOne(VehicleTypes::class);
    }
}
