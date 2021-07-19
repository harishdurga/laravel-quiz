<?php

namespace Harishdurga\LaravelQuiz\Tests\Feature;

use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\LaravelQuizFacade;
use Harishdurga\LaravelQuiz\Tests\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateQuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function create_quiz()
    {
        $author = Author::create(['name' => 'John Doe']);
        $quiz = LaravelQuizFacade::createQuiz(
            title: 'Test Quiz',
            code: 'test-quiz',
            isPublished: true,
            description: 'this is a test quiz',
            pointsToPass: 50,
            additionalData: ['total_marks' => 100, 'duration_in_minutes' => 120],
            authorId: $author->id,
            authorType: $author
        );
        $this->assertEquals(get_class($author), get_class($quiz->author));
    }
}
