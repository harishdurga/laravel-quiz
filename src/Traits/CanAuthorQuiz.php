<?php

namespace Harishdurga\LaravelQuiz\Traits;

use App\Models\QuizAuthor;

trait CanAuthorQuiz
{
    public function quizzes()
    {
        return $this->morphMany(QuizAuthor::class, 'author');
    }
}
