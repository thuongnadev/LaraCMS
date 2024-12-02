<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkuProductVariantOption extends Model
{
    use HasFactory;

    protected $table = 'skus_product_variant_options';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'sku_id',
        'product_variant_option_id',
    ];

    /**
     * Get the SKU that owns the SkuProductVariantOption.
     */
    public function sku()
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }

    /**
     * Get the ProductVariantOption that owns the SkuProductVariantOption.
     */
    public function productVariantOption()
    {
        return $this->belongsTo(ProductVariantOption::class, 'product_variant_option_id');
    }
}
