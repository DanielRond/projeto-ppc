<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates; // Importante
use App\States\ProposalState;    // Importante

class Course extends Model
{
    use HasStates; // Habilita o suporte a estados no modelo [1]

    protected $casts = [
        'status' => ProposalState::class, // Transforma a string do banco em objeto de estado [2, 3]
    ];
}
