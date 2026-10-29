<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sku', 'name', 'attributes',
        'price', 'old_price', 'stock', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
            'price'      => 'integer',
            'old_price'  => 'integer',
            'stock'      => 'integer',
            'is_active'  => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->old_price || $this->old_price <= $this->price) {
            return null;
        }

        return (int) round((1 - $this->price / $this->old_price) * 100);
    }
}
