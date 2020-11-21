<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ranges extends Model
{
    use HasFactory;

    protected $fillable = [
        'make_id',
        'name'
    ];

    public function model()
    {
    	return $this->hasMany(Models::class);
    }

    public function make()
    {
    	return $this->belongsTo(Makes::class);
    }
}
