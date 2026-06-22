<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\ProposalState;

class Course extends Model
{
    use HasStates;

    protected $fillable = [
        'nome',
        'carga_horaria_total',
        'quantidade_semestres',
        'justificativa',
        'impacto_social',
    ];

    protected $casts = [
        'status' => ProposalState::class,
    ];

    /**
     * Define o relacionamento: Um curso tem muitas disciplinas.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
