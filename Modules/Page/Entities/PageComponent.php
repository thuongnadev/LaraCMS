<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Component\Entities\Component;
use Modules\Component\Entities\ComponentConfiguration;

/**
 * @method static where(string $string, mixed $id)
 */
class PageComponent extends Model
{
    use HasFactory;

    protected $table = "comp_pages";

    protected $fillable = [
        'page_id',
        'component_id',
        'order',
    ];

    protected static function newFactory()
    {
        return \Modules\Page\Database\factories\PageComponentFactory::new();
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

    public function pageComponentConfigurationValues(): BelongsToMany
    {
        return $this->belongsToMany(
            ComponentConfiguration::class,
            'comp_page_values',
            'comp_page_id',
            'comp_config_id'
        )->withPivot('value', 'type');
    }
}
