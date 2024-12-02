<?php

namespace Modules\Post\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Category\Entities\Category;
use Modules\Comment\Entities\Comment;
use Modules\Tag\Entities\Tag;
use Modules\Category\Traits\Categorizable;
use Modules\Media\Entities\Media;
use Modules\Media\Entities\MediaHasModel;
use Modules\Media\Traits\DeletesMediaHasModels;

class Post extends Model
{
    use HasFactory,
        Categorizable,
        DeletesMediaHasModels;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'published_at',
        'author_id',
        'editor_id'
    ];

    protected $dates = ['published_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'media_has_models')
            ->withPivot('model_name');
    }

    public function mediaHasModel()
    {
        return $this->morphMany(MediaHasModel::class, 'model');
    }

    public function postImage()
    {
        return $this->morphToMany(Media::class, 'model', 'media_has_models')
            ->wherePivot('model_name', 'post_image')
            ->withPivot('model_name')
            ->orderBy('media_has_models.id', 'asc')
            ->take(1);
    }
}
