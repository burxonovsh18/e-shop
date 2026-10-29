<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug',
        'description', 'image', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** Ro'yxatda "dan ... so'm" ko'rsatish uchun */
    public function getMinPriceAttribute(): ?int
    {
        return $this->variants->where('is_active', true)->min('price');
    }

    public function getInStockAttribute(): bool
    {
        return $this->variants->where('is_active', true)->sum('stock') > 0;
    }
}
