<?php

namespace App\Models;

use App\Traits\PreventHardDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientCharge extends Model
{
    use HasFactory, PreventHardDelete;

    protected $guarded = ['id'];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function service()
    {
        return $this->belongsTo(ServiceCatalogue::class, 'service_catalogue_id');
    }

    public function drug()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reverser()
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }
}
