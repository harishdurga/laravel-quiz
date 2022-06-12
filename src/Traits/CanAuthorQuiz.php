<?php

namespace Harishdurga\LaravelQuiz\Traits;

trait CanAuthorQuiz
{
    public function quizzes()
    {
        return $this->morphMany(config('laravel-quiz.models.quiz_author'), 'author');
    }
}
