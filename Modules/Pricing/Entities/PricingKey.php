<?php

namespace Modules\Pricing\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PricingKey extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function pricingKeyValues(): HasMany
    {
        return $this->hasMany(PricingKeyValue::class, 'pricing_key_id');
    }
    public function pricings(): HasManyThrough
    {
        return $this->hasManyThrough(Pricing::class, PricingKeyValue::class, 'pricing_key_id', 'id', 'id', 'pricing_id');
    }

    public static function query(): Builder
    {
        return parent::query()
            ->withCount('pricings');
    }
}
