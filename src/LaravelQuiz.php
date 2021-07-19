<?php

namespace Harishdurga\LaravelQuiz;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizTopic;

class LaravelQuiz
{
    public function createQuiz(
        string $title,
        string $code,
        bool $isPublished = false,
        string|null $description = null,
        float $pointsToPass = 0,
        array|null $additionalData = null,
        int|null $authorId = null,
        Model|null $authorType = null
    ): Quiz {
        $data = [
            'title' => $title,
            'description' => $description,
            'code' => $code,
            'points_to_pass' => $pointsToPass,
            'additional_data' => is_array($additionalData) ? json_encode($additionalData) : null,
            'is_published' => $isPublished,
            'author_id' => $authorId,
            'author_type' => is_object($authorType) ? get_class($authorType) : null
        ];
        return Quiz::create($data);
    }

    /**
     * Get Quiz with it's unique code
     */
    public function getQuiz(string $code): ?Quiz
    {
        return Quiz::where('code', $code)->first();
    }

    public function createQuizTopic(string $topic, ?string $slug = null, ?int $parentId = null, bool $isActive = true): QuizTopic
    {
        return QuizTopic::create([
            'topic' => $topic,
            'slug' => $slug ?? Str::slug($topic, '-'),
            'parent_id' => $parentId,
            'is_active' => $isActive
        ]);
    }

    /**
     * Get QuizTopic with it's slug
     */
    public function getQuizTopic(string $slug): ?QuizTopic
    {
        return QuizTopic::where('slug', $slug)->first();
    }
}
