<?php

namespace App\Models;

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
}
