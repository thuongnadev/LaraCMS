<?php

namespace Modules\Component\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Page\Entities\Page;

class Component extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function newFactory()
    {
        return \Modules\Component\Database\factories\ComponentFactory::new();
    }

    public function pages()
    {
        return $this->belongsToMany(Page::class, 'comp_pages')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function pageComponents()
    {
        return $this->belongsToMany(Page::class, 'comp_pages')
            ->withPivot('order')
            ->orderBy('order');
    }

    public function configurations(): HasMany {
        return $this->hasMany(ComponentConfiguration::class, 'component_id');
    }
}
