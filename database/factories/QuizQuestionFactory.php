<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizQuestionFactory extends Factory
{
    protected $model = QuizQuestion::class;

    public function definition()
    {
        return [
            'question' => $this->faker->text(500),
            'points' => $this->faker->numberBetween(1, 10),
            'type' => 'single_option',
            'quiz_id' => 1
        ];
    }
}
