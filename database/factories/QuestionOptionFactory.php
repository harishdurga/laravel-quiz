<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    protected $model = QuestionOption::class;

    public function definition()
    {
        return [
            'question_id' => null,
            'name' => $this->faker->word,
            'media_url' => $this->faker->url,
            'is_correct' => $this->faker->numberBetween(0, 1),
            'media_type' => 'image',
        ];
    }
}
