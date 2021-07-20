<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\Quiz;
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
}
