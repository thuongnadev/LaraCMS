<?php

namespace Modules\Pricing\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PricingValue extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'status', 'is_check'];

    public function PricingKeyValues(): HasMany
    {
        return $this->hasMany(PricingKeyValue::class, 'pricing_value_id');
    }
    public function pricings(): HasManyThrough
    {
        return $this->hasManyThrough(Pricing::class, PricingKeyValue::class, 'pricing_value_id', 'id', 'id', 'pricing_id');
    }
    public static function query(): Builder
    {
        return parent::query()
            ->withCount('pricings');
    }
}
