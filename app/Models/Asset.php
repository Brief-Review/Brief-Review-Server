<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
