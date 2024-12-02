<?php

namespace Modules\Pricing\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingType extends Model
{
    use HasFactory;

    protected $table = 'pricing_types';

    protected $fillable = ['pricing_group_id', 'name'];

    public function pricingGroup(): BelongsTo
    {
        return $this->belongsTo(PricingGroup::class);
    }

    public function pricings(): HasMany
    {
        return $this->hasMany(Pricing::class);
    }
}
