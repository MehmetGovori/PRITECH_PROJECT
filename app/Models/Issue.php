<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'issue_tag');
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'issue_user');
    }

    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
              ->when($filters['priority'] ?? null, fn($q, $v) => $q->where('priority', $v))
              ->when($filters['tag_id'] ?? null, fn($q, $v) => $q->whereHas('tags', fn($tq) => $tq->where('tags.id', $v)))
              ->when($filters['search'] ?? null, fn($q, $v) => $q->where(fn($sq) =>
                  $sq->where('title', 'like', "%{$v}%")->orWhere('description', 'like', "%{$v}%")
              ));
    }
}
