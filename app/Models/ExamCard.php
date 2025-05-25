<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_type',
        'year',
        'semester',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}