<?php

namespace App\Traits;

use App\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Auditable
{
    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            Audit::create([
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'new_values' => json_encode($model->getAttributes()),
                'action' => 'created',
            ]);
        });

        static::updated(function (Model $model) {
            Audit::create([
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'old_values' => json_encode($model->getOriginal()),
                'new_values' => json_encode($model->getDirty()),
                'action' => 'updated',
            ]);
        });

        static::deleted(function (Model $model) {
            Audit::create([
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'old_values' => json_encode($model->getOriginal()),
                'action' => 'deleted',
            ]);
        });
    }
}
