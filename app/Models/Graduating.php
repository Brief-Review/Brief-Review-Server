<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduating extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'duration',
        'location',
        'partners',
        'manager',
        
    ];

    //Relationship to User
    public function users (){
        return $this->hasMany(User::class);
    }
    //Relationship to Briefing
    public function briefings (){
        return $this->hasMany(Briefing::class);
    }
    
}
