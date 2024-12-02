<?php

namespace Modules\Footer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Menu\Entities\Menu;
use Modules\SettingCompany\Entities\Business;

class FooterColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_id',
        'title',
        'content_type',
        'text_content',
        'image_content',
        'iframe_content',
        'google_map',
        'menu_id',
        'business_id',
        'order'
    ];

    public function menu(): BelongsTo {
        return $this->belongsTo(Menu::class, 'menu_id');
    }


    public function businesses(): BelongsTo {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function footer(): BelongsTo
    {
        return $this->belongsTo(Footer::class);
    }

    public function socialMedia(): HasMany
    {
        return $this->hasMany(SocialMedia::class);
    }
}
