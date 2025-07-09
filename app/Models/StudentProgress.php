<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    use HasFactory;

    protected $table = 'student_progresses';

    protected $fillable = [
        'student_id',
        'class_level',
        'semester',
        'year',
        'status',
    ];

    // (Opsional) tipe data cast
    protected $casts = [
        'student_id' => 'integer',
        'year'       => 'string',
    ];

    // Relasi ke Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Scope: progress aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope: progress terakhir (paling update)
    public function scopeTerakhir($query)
    {
        // Urutan: tahun, kelas, semester
        return $query->orderByDesc('year')
            ->orderByRaw("FIELD(class_level, 'XII','XI','X')")
            ->orderByRaw("FIELD(semester, 'genap','ganjil')");
    }

    // Helper: Ambil progress terakhir dari relasi student
    public static function lastProgress($studentId)
    {
        return static::where('student_id', $studentId)
            ->terakhir()
            ->first();
    }
}