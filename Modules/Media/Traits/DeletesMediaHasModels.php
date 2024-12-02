<?php

namespace Modules\Media\Traits;

use Modules\Media\Entities\MediaHasModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait DeletesMediaHasModels
{
    public static function bootDeletesMediaHasModels()
    {
        static::deleted(function (Model $model) {
            self::deleteMediaForModel($model);
        });
    }

    public static function deleteMediaForModel(Model $model)
    {
        MediaHasModel::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->delete();
    }

    public static function deleteMediaForModels(Collection $models)
    {
        MediaHasModel::where('model_type', get_class($models->first()))
            ->whereIn('model_id', $models->pluck('id'))
            ->delete();
    }
}