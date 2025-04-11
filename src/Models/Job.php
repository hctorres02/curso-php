<?php

namespace App\Models;

use App\Enums\JobStatus;
use App\Enums\JobType;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use Auditable;

    protected $fillable = [
        'callable',
        'params',
        'type',
        'status',
    ];

    protected $casts = [
        'params' => 'array',
        'type' => JobType::class,
        'status' => JobStatus::class,
    ];

    protected $appends = [
        'is_done',
    ];

    public static function toSearch(array $params): array
    {
        $data = static::query()
            ->when($params['status'], fn ($query, $status) => $query->where('status', $status))
            ->paginate(10)
            ->appends(array_filter($params))
            ->toArray();

        $data['statuses'] = JobStatus::toArray();

        return array_merge($params, $data);
    }

    public function isDone(): Attribute
    {
        return Attribute::make(get: fn () => $this->status === JobStatus::DONE);
    }

    public function scopeDone(Builder $query): Builder
    {
        return $query->where('status', JobStatus::DONE);
    }

    public function scopeFailed(Builder $query): Builder
    {
        return $query->where('status', JobStatus::FAILED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', JobStatus::PENDING);
    }
}
