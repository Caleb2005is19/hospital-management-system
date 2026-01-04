<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispensedMedicine extends Model
{
    //
    public function drug()
    {
        return $this->belongsTo(Drug::class, 'drug_id');
    }
}
