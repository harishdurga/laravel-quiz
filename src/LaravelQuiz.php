<?php

namespace Harishdurga\LaravelQuiz;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Eloquent\Model;

class LaravelQuiz
{
    /**
     * @return 
     */
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
        return Quiz::factory()->create($data);
    }
}
