<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    protected $fillable = [
        'product_id', 'type', 'priority', 'message', 'is_read'
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
