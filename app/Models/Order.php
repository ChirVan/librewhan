<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'customer_name',
        'order_type',
        'payment_mode',
        'subtotal',
        'total',
        'amount_paid',
        'change_due',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
