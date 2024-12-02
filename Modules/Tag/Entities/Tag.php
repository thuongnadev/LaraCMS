<?php

namespace Modules\Tag\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Post\Entities\Post;
use Modules\ProductVps\Entities\ProductVps;

/**
 * @method static firstOrCreate(array $array)
 */
class Tag extends Model
{
    use HasFactory;

    protected function rules(): array {
        return [];
    }

    protected $fillable = [
        'name', 'slug'
    ];

    protected static function newFactory()
    {
        return \Modules\Tag\Database\factories\TagFactory::new();
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function products()
    {
        return $this->morphedByMany(ProductVps::class, 'taggable');
    }
}
