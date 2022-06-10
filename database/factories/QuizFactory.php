<?php

namespace Harishdurga\LaravelQuiz\Database\Factories;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    public function definition()
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph,
            'total_marks' => 0,
            'pass_marks' => 0,
            'max_attempts' => 0,
            'is_published' => 1,
            'media_url' => $this->faker->imageUrl(300, 300),
            'media_type' => 'image',
            'duration' => 0,
            'valid_from' => date('Y-m-d H:i:s'),
            'valid_upto' => null,
            'negative_marking_settings' => [
                'enable_negative_marks' => true,
                'negative_marking_type' => 'fixed',
                'negative_mark_value' => 0,
            ]
        ];
    }
}
