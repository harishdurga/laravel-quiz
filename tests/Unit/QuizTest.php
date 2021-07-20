<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function quiz()
    {
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
        ]);
        $this->assertEquals(1, Quiz::count());
        $this->assertEquals('Sample Quiz', Quiz::find($quiz->id)->title);
    }

    /** @test */
    function quiz_topics_relation()
    {
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
        ]);
        $topic_one = Topic::factory()->make()->create([
            'topic' => 'Topic One',
            'slug' => 'topic-one',
        ]);
        $topic_two = Topic::factory()->make()->create([
            'topic' => 'Topic Two',
            'slug' => 'topic-two',
        ]);
        $quiz->topics()->attach([$topic_one->id, $topic_two->id]);
        $this->assertEquals(2, $quiz->topics()->count());
    }

    /** @test */
    function quiz_questions_relation()
    {
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
        ]);
        $question_one = Question::factory()->create();
        $question_two = Question::factory()->create();
        $quiz_question_one = QuizQuestion::factory()->make()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
        ]);
        $quiz_question_two = QuizQuestion::factory()->make()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
        ]);
        $this->assertEquals(2, $quiz->questions()->count());
    }
}
