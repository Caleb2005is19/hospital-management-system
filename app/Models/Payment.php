<?php

namespace App\Models;

use App\Traits\PreventHardDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, PreventHardDelete;

    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function session()
    {
        return $this->belongsTo(CashierSession::class, 'cashier_session_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}
