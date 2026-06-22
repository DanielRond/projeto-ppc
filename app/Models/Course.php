<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\States\ProposalState;

class Course extends Model
{
    use HasStates;

    // Adicione isso aqui:
    protected $fillable = [
        'nome',
        'carga_horaria_total',
        'quantidade_semestres',
        'justificativa',
        'impacto_social',
        // 'status' não precisa estar aqui, o pacote de estados cuida dele sozinho
    ];

    protected $casts = [
        'status' => ProposalState::class,
    ];
}
