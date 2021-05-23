<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Illuminate\Support\Str;
use Harishdurga\LaravelQuiz\Models\QuizTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizTopicFactory extends Factory
{
    protected $model = QuizTopic::class;

    public function definition()
    {
        $topic = $this->faker->words(4, true);
        return [
            'topic' => $topic,
            'slug' => Str::slug($topic, '-'),
            'parent_id' => null,
            'is_active' => $this->faker->numberBetween(0, 1)
        ];
    }
}
