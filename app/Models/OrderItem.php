<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        // Add more fields as needed
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
