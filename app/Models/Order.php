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
        'customer_phone',
        'order_type',
        'payment_method',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total',
        'amount_paid',
        'change_due',
        'status',
        'user_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_due' => 'decimal:2',
    ];

    // relation names: keep items() for your views/controller expectations
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // alternative accessor for clearer naming
    public function orderItems()
    {
        return $this->items();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
