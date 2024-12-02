<?php

namespace Modules\Pricing\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pricing extends Model
{
    use HasFactory;

    protected $fillable = ['pricing_type_id', 'pricing_group_id', 'name', 'show'];

    public function pricingType(): BelongsTo
    {
        return $this->belongsTo(PricingType::class, 'pricing_type_id');
    }

    public function pricingGroup(): BelongsTo
    {
        return $this->belongsTo(PricingGroup::class, 'pricing_group_id');
    }

    public function pricingKeyValues(): HasMany
    {
        return $this->hasMany(PricingKeyValue::class, 'pricing_id');
    }

    
}
