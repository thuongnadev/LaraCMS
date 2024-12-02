<?php

namespace Modules\Pricing\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingKeyValue extends Model
{
    use HasFactory;

    protected $fillable = ['pricing_id', 'pricing_key_id', 'pricing_value_id', 'pricing_content_id', 'sort'];

    public function pricing(): BelongsTo
    {
        return $this->belongsTo(Pricing::class, 'pricing_id');
    }

    public function pricingKey(): BelongsTo
    {
        return $this->belongsTo(PricingKey::class,'pricing_key_id');
    }

    public function pricingValue(): BelongsTo
    {
        return $this->belongsTo(PricingValue::class,'pricing_value_id');
    }

    public function pricingContent(): BelongsTo
    {
        return $this->belongsTo(PricingContent::class,'pricing_content_id');
    }
}
