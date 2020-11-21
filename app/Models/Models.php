<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    use HasFactory;

    public function derivative()
    {
    	return $this->hasMany(Derivatives::class);
    }

    public function range()
    {
    	return $this->belongsTo(Ranges::class);
    }
}
