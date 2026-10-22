<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'headline', 'short_bio', 'full_bio', 'location', 'availability', 'avatar_path', 'resume_path'];
}
