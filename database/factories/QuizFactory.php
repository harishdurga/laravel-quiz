<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition()
    {
        return [
            'title' => $this->faker->text(200),
            'description' => $this->faker->text(1000),
            'code' => $this->faker->unique()->text,
            'points_to_pass' => $this->faker->numberBetween(10, 100),
            'additional_data' => json_encode(['first_name' => 'hello', 'last_name' => 'world']),
            'is_published' => $this->faker->numberBetween(0, 1)
        ];
    }
}
