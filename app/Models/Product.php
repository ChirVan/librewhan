<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id','name','slug','sku','base_price','customizable','status',
        'current_stock','low_stock_alert','display_order','description'
    ];

    protected $casts = [
        'customizable' => 'boolean',
        'base_price' => 'decimal:2',
        'current_stock' => 'integer',
        'low_stock_alert' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // safely decrement stock, return true/false
    public function decrementStock(int $qty, ?int $userId = null, string $reason = 'Sale'): bool
    {
        if ($qty <= 0) return true;

        // check stock
        if ($this->current_stock < $qty) {
            return false;
        }

        $old = $this->current_stock;
        $this->current_stock = max(0, $this->current_stock - $qty);
        $this->save();

        // log movement
        StockMovement::create([
            'product_id' => $this->id,
            'old_quantity' => $old,
            'new_quantity' => $this->current_stock,
            'difference' => $this->current_stock - $old,
            'reason' => $reason,
            'adjustment_type' => 'subtract',
            'user_id' => $userId,
        ]);

        return true;
    }

    protected static function booted() {
        static::saving(function($model){
            if (!$model->slug) {
                $model->slug = str($model->name)->slug();
            }
        });
    }
}
