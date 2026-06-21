<?php

namespace App\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ProposalState extends State
{
public static function config(): StateConfig
{
return parent::config()
->default(Submitted::class) // Estado inicial
->allowTransition(Submitted::class, UnderReview::class)
->allowTransition(UnderReview::class, AdjustmentRecommended::class)
->allowTransition(AdjustmentRecommended::class, UnderReview::class)
->allowTransition(UnderReview::class, FinalDecision::class);
}
}
