<?php

namespace Modules\Header\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeaderContact extends Model
{
    use HasFactory;

    protected $fillable = ['header_id', 'name', 'value'];

    public function header(): BelongsTo
    {
        return $this->belongsTo(Header::class);
    }
}
