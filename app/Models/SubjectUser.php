<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SubjectUser extends Pivot
{
    public $timestamps = true;
    protected $table = 'subject_user';

    protected $fillable = [
        'user_id',
        'subject_id',
        'year',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    // RELASI KE USER
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // RELASI KE SUBJECT
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
