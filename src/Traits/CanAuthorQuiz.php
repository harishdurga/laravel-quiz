<?php

namespace Harishdurga\LaravelQuiz\Traits;

use Harishdurga\LaravelQuiz\Models\QuizAuthor;


trait CanAuthorQuiz
{
    public function quizzes()
    {
        return $this->morphMany(QuizAuthor::class, 'author');
    }
}
