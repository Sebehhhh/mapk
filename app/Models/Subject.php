<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'semester',
        'class_level',
    ];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}