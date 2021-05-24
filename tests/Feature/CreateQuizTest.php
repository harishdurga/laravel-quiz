<?php

namespace Harishdurga\LaravelQuiz\Tests\Feature;

use Harishdurga\LaravelQuiz\LaravelQuizFacade;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateQuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function create_quiz()
    {
        $model = Quiz::factory()->make();
        $quiz = LaravelQuizFacade::createQuiz(
            title: 'Test Quiz',
            code: 'test-quiz',
            isPublished: true,
            description: 'this is a test quiz',
            pointsToPass: 50,
            additionalData: ['total_marks' => 100, 'duration_in_minutes' => 120],
            authorId: 1,
            authorType: $model
        );
        $this->assertEquals(get_class($model), $quiz->author_type);
    }
}
