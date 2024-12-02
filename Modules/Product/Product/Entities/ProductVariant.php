<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Get all of the product variant options for this variant.
     */
    public function productVariantOptions()
    {
        return $this->belongsTo(ProductVariantOption::class, 'variant_id');
    }

    /**
     * Get the SKUs for this variant.
     */
    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class);
    }
}

