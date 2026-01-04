<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
