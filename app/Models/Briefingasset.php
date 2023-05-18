<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefingasset extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link',
        'image',         
        'briefing_id',       
    ];

    //Relationship to Briefing
    public function briefings (){
        return $this->belongsTo(Briefing::class);
    }
}
