<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition()
    {

        return [
            'quiz_id' => null,
            'question_id' => null,
            'marks' => 0,
            'is_optional' => false,
        ];
    }
}
