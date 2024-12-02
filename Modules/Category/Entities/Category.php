<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Post\Entities\Post;
use Modules\ProductVps\Entities\ProductVps;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'category_type', 'status']; 

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categorizable');
    }

    public function products()
    {
        return $this->morphedByMany(ProductVps::class, 'categorizable');
    }
}
