<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'status',
        'featured',
        'display_order',
        'description',
        'customization_json',
    ];

    protected $casts = ['featured' => 'boolean'];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function scopeActive($q){
        return $q->where('status','active');
    }

     // Auto slug if empty
    protected static function booted() {
        static::saving(function($cat){
            if (!$cat->slug) {
                $cat->slug = str($cat->name)->slug();
            }
        });
    }
}
