<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'year',
        'semester',
        'attendance',
        'assignment',
        'mid_exam',
        'final_exam',
        'total',
        'final_score',
        'rank',
    ];

    protected $casts = [
        'attendance' => 'integer',
        'assignment' => 'integer',
        'mid_exam' => 'integer',
        'final_exam' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get nilai akhir dengan handling null values
     */
    public function getFinalScoreAttribute()
    {
        // Jika ada nilai yang null, tidak hitung nilai akhir
        if (is_null($this->attendance) || is_null($this->assignment) || 
            is_null($this->mid_exam) || is_null($this->final_exam)) {
            return null;
        }

        return round(
            ($this->attendance * 0.10) +
            ($this->assignment * 0.20) +
            ($this->mid_exam * 0.30) +
            ($this->final_exam * 0.40),
            2
        );
    }

    /**
     * Check if all scores are filled
     */
    public function isComplete()
    {
        return !is_null($this->attendance) && !is_null($this->assignment) && 
               !is_null($this->mid_exam) && !is_null($this->final_exam);
    }
}