<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'repoGithub',
        'feedback',
        'graduating_id',
        'user_id',
    ];
}
