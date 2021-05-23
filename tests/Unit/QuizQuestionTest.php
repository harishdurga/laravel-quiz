<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Models\QuizQuestionOption;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizQuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_question_has_options()
    {
        $quiz = Quiz::factory()->create(['code' => 'test123']);
        $question = QuizQuestion::factory()->create(['quiz_id' => $quiz->id]);
        QuizQuestionOption::factory()->count(4)->create(['quiz_question_id' => $question->id]);
        $this->assertEquals(4, $question->options->count());
    }
}
