<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftHandover extends Model
{
    use HasFactory;

    // 👇 ADD THIS FUNCTION 👇
    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
}
