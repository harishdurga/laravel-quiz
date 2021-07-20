<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
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
}
