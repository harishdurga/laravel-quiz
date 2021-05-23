<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Models\QuizTopic;
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

    /** @test */
    function a_quiz_has_questions()
    {
        $quiz = Quiz::factory()->create(['code' => 'test123']);
        QuizQuestion::factory()->count(10)->create(['quiz_id' => $quiz->id]);
        $this->assertEquals(10, $quiz->questions->count());
    }

    /** @test */
    function a_quiz_has_topics()
    {
        $quiz = Quiz::factory()->create(['code' => 'test123']);
        $quizTopics = QuizTopic::factory()->count(3)->create()->pluck('id');
        $quiz->quiz_topics()->attach($quizTopics);
        $this->assertEquals(3, $quiz->quiz_topics->count());
    }
}
