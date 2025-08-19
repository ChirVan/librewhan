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
        'customizable' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected static function booted() {
        static::saving(function($model){
            if (!$model->slug) {
                $model->slug = str($model->name)->slug();
            }
        });
    }
}

