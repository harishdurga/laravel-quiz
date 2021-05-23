<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Harishdurga\LaravelQuiz\Models\QuizQuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionOptionFactory extends Factory
{
    protected $model = QuizQuestionOption::class;

    public function definition()
    {
        return [
            'option' => $this->faker->text(100),
            'is_correct' => $this->faker->numberBetween(0, 1),
            'quiz_question_id' => 1
        ];
    }
}
