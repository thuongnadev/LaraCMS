<?php

namespace Modules\Pricing\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingGroup extends Model
{
    use HasFactory;

    protected $table = 'pricing_groups';

    protected $fillable = ['name', 'sort'];

    public function pricingTypes(): HasMany
    {
        return $this->hasMany(PricingType::class);
    }

    public function pricings(): HasMany
    {
        return $this->hasMany(Pricing::class);
    }
}
