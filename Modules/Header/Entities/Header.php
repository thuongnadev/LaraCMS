<?php

namespace Modules\Header\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Header extends Model
{
    use HasFactory;

    protected $fillable = ['logo_size', 'status', 'name', 'background_color'];

    public function contacts(): HasMany
    {
        return $this->hasMany(HeaderContact::class);
    }

}
