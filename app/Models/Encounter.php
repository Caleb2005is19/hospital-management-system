<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Encounter belongs to a patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Encounter has one triage record
    public function triage()
    {
        return $this->hasOne(Triage::class);
    }

    // Doctor assigned
    public function doctor()
    {
        return $this->belongsTo(User::class, 'assigned_doctor_id');
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function charges()
    {
        return $this->hasMany(PatientCharge::class, 'encounter_id');
    }
}
