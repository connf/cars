<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;

    protected $dates = [
    	'date_on_forecourt'
    ]

    public function colour()
    {
    	return $this->hasOne(Colours::class);
    }

    public function type()
    {
    	return $this->hasOne(VehicleTypes::class);
    }
}
