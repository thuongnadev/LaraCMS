<?php

namespace Modules\ProductVps\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Category\Entities\Category;
use Modules\Category\Traits\Categorizable;
use Modules\Media\Entities\Media;
use Modules\Media\Entities\MediaHasModel;
use Modules\Media\Traits\DeletesMediaHasModels;

class ProductVps extends Model
{
    use Categorizable,
        HasFactory,
        DeletesMediaHasModels;

    protected $fillable = ['name', 'slug'];

    public function productImage()
    {
        return $this->morphToMany(Media::class, 'model', 'media_has_models')
            ->wherePivot('model_name', 'product_image')
            ->withPivot('model_name')
            ->orderBy('media_has_models.id', 'asc')
            ->take(1);
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
}
