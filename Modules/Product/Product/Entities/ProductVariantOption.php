<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariantOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id', 'product_id', 'name', 'value'
    ];

     /**
     * Get the product variant that owns the option.
     */
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Get the product variant that owns the option.
     */
    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class, 'variant_id');
    }

    /**
     * Get all of the SKUs that are assigned this variant option.
     */
    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'skus_product_variant_options', 'product_variant_option_id', 'sku_id');
    }
}
