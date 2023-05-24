<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link',
        'image',
        'tags', 
        'user_id',       
    ];

    //Relationship to User
    public function users (){
        return $this->belongsTo(User::class);
    }
    
}
