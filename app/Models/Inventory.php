<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'category',
        'dosage_form',
        'stock_quantity',
        'unit_price',
    ];

    public function logs()
    {
        return $this->hasMany(InventoryLog::class, 'inventory_id')->latest();
    }
}

