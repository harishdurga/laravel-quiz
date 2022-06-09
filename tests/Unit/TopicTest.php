<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function topic()
    {
        $topic = Topic::factory()->create([
            'name' => 'Test Topic',
        ]);
        $this->assertEquals('Test Topic', $topic->name);
    }

    /** @test */
    function topic_parent_child_relation()
    {
        $parentTopic = Topic::factory()->create([
            'name' => 'Parent Topic',
        ]);
        $parentTopic->children()->saveMany([
            Topic::factory()->make(['name' => 'Child Topic 1']),
            Topic::factory()->make(['name' => 'Child Topic 2']),
        ]);
        $this->assertEquals(2, $parentTopic->children()->count());
    }

    /** @test */
    function topic_question_relation()
    {
        $topic = Topic::factory()->create([
            'name' => 'Test Topic',
        ]);
        $question1 = Question::factory()->create([
            'name' => 'Test Question',
        ]);
        $question2 = Question::factory()->create([
            'name' => 'Test Question',
        ]);
        $question3 = Question::factory()->create([
            'name' => 'Test Question',
        ]);
        $topic->questions()->attach($question1);
        $topic->questions()->attach([$question2->id, $question3->id]);
        $this->assertEquals(3, $topic->questions()->count());
    }
}
