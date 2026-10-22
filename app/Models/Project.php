<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'summary', 'problem', 'solution', 'result', 'role', 'cover_path', 'cover_public_id', 'demo_url', 'repository_url', 'status', 'published_at', 'is_featured', 'sort_order', 'created_by', 'updated_by'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_featured' => 'boolean'];
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published')->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProjectMedia::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
