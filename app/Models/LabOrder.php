<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOrder extends Model
{
    protected $guarded = [];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}

