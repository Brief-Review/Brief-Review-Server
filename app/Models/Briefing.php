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

    //Relationship to Graduating
    public function graduatings() {
        return $this->belongsTo(Graduating::class);
    }
    //Relationship to User
    public function users() {
        return $this->belongsTo(User::class);
    }
}
