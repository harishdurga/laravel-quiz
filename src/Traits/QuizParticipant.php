<?php

namespace Harishdurga\LaravelQuiz\Traits;

use Harishdurga\LaravelQuiz\Models\QuizAttempt;

trait QuizParticipant
{
    public function quiz_attempts()
    {
        return $this->morphMany(QuizAttempt::class, 'participant');
    }
}
