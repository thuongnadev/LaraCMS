<?php

namespace Modules\Component\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentConfigOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'comp_config_id',
        'option_label',
        'option_value',
    ];

    protected $table = 'comp_config_options';

    protected static function newFactory()
    {
        return \Modules\Component\Database\factories\ComponentConfigOptionFactory::new();
    }

    public function config(): BelongsTo
    {
        return $this->belongsTo(ComponentConfiguration::class, 'comp_config_id');
    }
}
