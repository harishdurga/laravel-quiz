<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function topic()
    {
        $topic = Topic::factory()->create([
            'topic' => 'Test Topic',
        ]);
        $this->assertEquals('Test Topic', $topic->topic);
    }

    /** @test */
    function topic_parent_child_relation()
    {
        $parentTopic = Topic::factory()->create([
            'topic' => 'Parent Topic',
        ]);
        $parentTopic->children()->saveMany([
            Topic::factory()->make(['topic' => 'Child Topic 1']),
            Topic::factory()->make(['topic' => 'Child Topic 2']),
        ]);
        $this->assertEquals(2, $parentTopic->children()->count());
    }
}
