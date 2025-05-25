<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nisn',
        'gender',
        'class',
        'address',
        'birth_date',
        'phone',
        'place_of_birth',
        'religion',
        'province',
        'district',
        'sub_district',
        'village',
        'origin_school_name',
        'origin_school_address',
        'graduation_year',
        'photo',
    ];

    /**
     * Relasi ke user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke nilai (scores).
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Relasi ke kartu ujian.
     */
    public function examCards()
    {
        return $this->hasMany(ExamCard::class);
    }

    /**
     * Relasi ke data orang tua siswa.
     */
    public function parent()
    {
        return $this->hasOne(StudentParent::class);
    }
}