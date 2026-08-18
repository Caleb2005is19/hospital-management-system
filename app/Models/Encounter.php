<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function vitals()
    {
        return $this->hasMany(Vital::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function charges()
    {
        return $this->hasMany(PatientCharge::class, 'encounter_id');
    }

    public function patientCharges()
    {
        return $this->hasMany(PatientCharge::class, 'encounter_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'encounter_id');
    }
}
