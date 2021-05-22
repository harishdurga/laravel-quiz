<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_quiz_has_a_code()
    {
        $quiz = Quiz::factory()->create([
            'title' => 'Test Quiz',
            'description' => 'This is for testing the quiz',
            'code' => 'test123',
            'points_to_pass' => 10,
            'additional_data' => json_encode(['first_name' => 'hello', 'last_name' => 'world']),
            'is_published' => 1
        ]);
        $this->assertEquals('test123', $quiz->code);
    }
}
