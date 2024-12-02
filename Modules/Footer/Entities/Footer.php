<?php

namespace Modules\Footer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Footer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'background_color',
        'title_color',
        'base_color'
    ];

    public function columns(): HasMany {
        return $this->hasMany(FooterColumn::class)->orderBy('order');
    }
}
