<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuizTopicQuiz extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_topic_quiz', parent::getTable());
    }
}
