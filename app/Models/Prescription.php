<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    public function drug()
    {
        return $this->belongsTo(\App\Models\Inventory::class, \Illuminate\Support\Facades\Schema::hasColumn("prescriptions", "inventory_id") ? "inventory_id" : "drug_id");
    }

    public function inventory()
    {
        return $this->drug();
    }

    protected $guarded = [];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }
}

