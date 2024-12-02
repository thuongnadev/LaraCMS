<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sku extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'sku', 'price', 'stock'
    ];

    /**
     * Get the product that owns the SKU.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all of the product variant options for the SKU.
     */
    public function productVariantOptions()
    {
        return $this->belongsToMany(ProductVariantOption::class, 'skus_product_variant_options', 'sku_id', 'product_variant_option_id');
    }
}
