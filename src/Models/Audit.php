<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    public $fillable = [
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'action',
    ];

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function oldValues(): Attribute
    {
        return Attribute::make(get: fn (?string $old_values) => json_decode(strval($old_values)));
    }

    public function newValues(): Attribute
    {
        return Attribute::make(get: fn (?string $new_values) => json_decode(strval($new_values)));
    }
}
