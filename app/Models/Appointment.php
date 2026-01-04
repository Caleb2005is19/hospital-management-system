<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public function patient()
    {
        // This allows us to call $appointment->patient->name
        return $this->belongsTo(User::class, 'patient_id');
    }
}
