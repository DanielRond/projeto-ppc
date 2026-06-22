<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'course_id',
        'nome',
        'carga_horaria',
        'semestre',
    ];

    /**
     * Define a relação inversa: A disciplina pertence a um curso.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
