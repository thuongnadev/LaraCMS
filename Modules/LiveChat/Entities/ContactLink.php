<?php

namespace Modules\LiveChat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'facebook_messenger_link',
        'zalo_link',
        'phone_number',
        'text_color',
        'position',
        'bottom_offset'
    ];
    
    protected static function newFactory()
    {
        
    }
}
