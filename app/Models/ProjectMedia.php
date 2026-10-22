<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMedia extends Model
{
    protected $fillable = ['project_id', 'path', 'public_id', 'media_type', 'alt_text', 'caption', 'width', 'height', 'sort_order'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
