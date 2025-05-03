<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'product_name',
        'product_slug',
        'description',
        'image',
        'ai_1',
        'ai_2',
        'ai_3',
        'ai_4',
        'ai_5',
        'ai_6',
        'product_size',
        'actual_price',
        'discounted_price',
        'sale',
        'stock',
        'sku',
        'views',
        'is_featured',
        'number_of_orders',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }

    public function productInfos(): HasMany
    {
        return $this->hasMany(ProductInfo::class, 'product_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
}
