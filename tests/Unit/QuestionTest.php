<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function question()
    {
        $question = Question::factory()->create();
        $this->assertEquals(Question::count(), 1);
    }

    /** @test */
    function question_question_type_relation()
    {
        $questionType = QuestionType::factory()->create([
            'question_type' => 'fill_the_blank'
        ]);
        $questionType->questions()->saveMany([
            Question::factory()->make(),
            Question::factory()->make()
        ]);
        $this->assertEquals($questionType->questions->count(), 2);
    }

    /** @test */
    function question_and_topics_relation()
    {
        $topic1 = Topic::factory()->create(['topic' => 'Test Topic One']);
        $topic2 = Topic::factory()->create(['topic' => 'Test Topic Two']);
        $question = Question::factory()->create();
        $question->topics()->attach($topic1);
        $question->topics()->attach($topic2);
        $this->assertEquals(2, $question->topics->count());
    }

    /** @test */
    function question_and_question_options_relation()
    {
        $question = Question::factory()->create();
        $question->options()->saveMany([
            QuestionOption::factory()->make([
                'question_id' => $question->id,
            ]),
            QuestionOption::factory()->make([
                'question_id' => $question->id,
            ]),
        ]);
        $this->assertEquals(2, $question->options->count());
    }
}
