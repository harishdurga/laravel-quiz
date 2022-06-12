<?php

namespace Harishdurga\LaravelQuiz\Traits;

use Harishdurga\LaravelQuiz\Models\QuizAttempt;

trait QuizParticipant
{
    public function quiz_attempts()
    {
        return $this->morphMany(config('laravel-quiz.models.quiz_attempt'), 'participant');
    }
}
