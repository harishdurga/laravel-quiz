<?php

namespace Harishdurga\LaravelQuiz\Traits;

use Harishdurga\LaravelQuiz\Models\Ownership;

trait BeAOwner
{
    public function ownerships(){
        return $this->morphMany(Ownership::class,'owner');
    }
}