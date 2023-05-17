<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'graduating_id',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relationship to Graduating
    public function graduatings() {
        return $this->belongsTo(Graduating::class);
    }
    //Relationship to Briefing
    public function briefings() {
        return $this->hasMany(Briefing::class);
    }
    //Relationships to Resource
    public function resources() {
        return $this->hasMany(Asset::class);
    }
}
