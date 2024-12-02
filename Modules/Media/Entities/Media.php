<?php

namespace Modules\Media\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Post\Entities\Post;
use Modules\Product\Entities\Product;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'name',
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'alt_text',
        'description',
        'uploaded_by',
        'is_public',
    ];

    protected $casts = [
        'file_name' => 'array',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function mediaHasModels()
    {
        return $this->hasMany(MediaHasModel::class, 'media_id');
    }

    public function media(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'media_has_models', 'media_id', 'model_id')
            ->withPivot('model_type')
            ->withTimestamps();
    }

    // public function Media(): BelongsToMany
    // {
    //     return $this->belongsToMany(Media::class, '_has_models', 'model_id', 'folder_id')
    //         ->where('folder_has_models.model_type', static::class)
    //         ->withPivot('model_type')
    //         ->withTimestamps();
    // }

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, 'model', 'media_has_models');
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'model', 'media_has_models');
    }
}
