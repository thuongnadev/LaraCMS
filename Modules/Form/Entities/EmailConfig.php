<?php

namespace Modules\Form\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailConfig extends Model
{
    use HasFactory;
    protected $fillable = [
        'mailer_type',
        'configurations',
        'is_default',
    ];
    protected $casts = [
        'configurations' => 'array',
    ];
}
