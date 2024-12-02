<?php

namespace Modules\Footer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialMedia extends Model
{
    use HasFactory;

    protected $fillable = ['footer_column_id', 'platform', 'icon', 'url'];

    public function footerColumn(): BelongsTo
    {
        return $this->belongsTo(FooterColumn::class);
    }
}
