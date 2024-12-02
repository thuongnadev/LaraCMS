<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\Comment;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'content',
        'status', 'seo_title', 'seo_description', 'seo_keywords',
        'published_at', 'user_id',
    ];

    // public function categories(): MorphToMany
    // {
    //     return $this->morphToMany(Category::class, 'categorizable');
    // }

    /**
     * Get the product variants for the product.
     */
    // public function productVariants(): HasMany
    // {
    //     return $this->hasMany(ProductVariant::class);
    // }

    public function productVariantOptions(): HasMany
    {
        return $this->hasMany(ProductVariantOption::class);
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->user_id = Auth::id();
        });

        static::saving(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
