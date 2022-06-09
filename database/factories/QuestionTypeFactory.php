<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Illuminate\Support\Str;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionTypeFactory extends Factory
{
    protected $model = QuestionType::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(1, true)
        ];
    }
}
