<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Database\Factories\QuizFactory;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizTopic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizTopicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_quiz_topic_has_a_slug()
    {
        $quizTopic = QuizTopic::factory()->create([
            'topic' => 'Test Quiz Topic',
            'slug' => 'test-quiz-topic'
        ]);
        $this->assertEquals('test-quiz-topic', $quizTopic->slug);
    }

    /** @test */
    function a_quiz_topic_has_a_children()
    {
        $quizTopic = QuizTopic::factory()->create([
            'topic' => 'Test Quiz Topic',
            'slug' => 'test-quiz-topic'
        ]);
        QuizTopic::factory()->count(5)->create([
            'parent_id' => $quizTopic->id
        ]);
        $this->assertEquals(5, $quizTopic->children->count());
    }

    /** @test */
    function a_quiz_topic_has_a_parent()
    {
        $parent = QuizTopic::factory()->create([
            'topic' => 'Test Quiz Topic',
            'slug' => 'test-quiz-topic'
        ]);

        $child = QuizTopic::factory()->create([
            'parent_id' => $parent->id
        ]);
        $this->assertEquals($parent->slug, $child->parent->slug);
    }

    /** @test */
    function a_quiz_topic_has_a_quizzes()
    {
        $quizTopic = QuizTopic::factory()->create([
            'topic' => 'Test Quiz Topic',
            'slug' => 'test-quiz-topic'
        ]);
        $quizzes = Quiz::factory()->count(5)->create()->pluck('id');
        $quizTopic->quizzes()->attach($quizzes);
        $this->assertEquals(5, $quizTopic->quizzes->count());
    }
}
