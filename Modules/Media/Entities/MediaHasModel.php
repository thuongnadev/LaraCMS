<?php

namespace Modules\Media\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MediaHasModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'media_id',
        'model_name'
    ];

    protected $casts = [
        'model_id' => 'integer',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
