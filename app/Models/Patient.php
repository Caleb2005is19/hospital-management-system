<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $guarded = [];

    // A patient can have many encounters over time
    public function encounters()
    {
        return $this->hasMany(Encounter::class);
    }

    // Quick helper to get their latest active visit
    public function latestEncounter()
    {
        return $this->hasOne(Encounter::class)->latestOfMany();
    }
}
