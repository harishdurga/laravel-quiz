<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Tests\Models\Author;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Harishdurga\LaravelQuiz\Models\QuizAttemptAnswer;

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

    /** @test */
    function quiz_attempts_relation()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        $userTwo = Author::create(
            ['name' => "John Doe"]
        );
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
        ]);
        $question_one = Question::factory()->create();
        $question_one->options()->save(QuestionOption::factory()->make([
            'is_correct' => true
        ]));
        $question_two = Question::factory()->create();
        $question_two->options()->save(QuestionOption::factory()->make([
            'is_correct' => true
        ]));
        $quiz_question_one = QuizQuestion::factory()->make()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
        ]);
        $quiz_question_two = QuizQuestion::factory()->make()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
        ]);
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user),
        ]);
        $attemptTwo = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $userTwo->id,
            'participant_type' => get_class($userTwo),
        ]);
        $this->assertEquals(1, $user->quiz_attempts()->count());
        $this->assertEquals($user->id, $attempt->participant->id);
        $this->assertEquals(2, $quiz->attempts()->count());
    }

    /** @test */
    function quiz_attempt_answers()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        $userTwo = Author::create(
            ['name' => "John Doe"]
        );
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
        ]);
        $question_one = Question::factory()->create();
        $question_one->options()->save(QuestionOption::factory()->make([
            'is_correct' => true,
            'option' => 'Doe'
        ]));
        $question_two = Question::factory()->create();
        $question_two->options()->save(QuestionOption::factory()->make([
            'is_correct' => true,
            'option' => 'John'
        ]));
        $quiz_question_one = QuizQuestion::factory()->make()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
        ]);
        $quiz_question_two = QuizQuestion::factory()->make()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
        ]);
        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user),
        ]);
        $attemptTwo = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $userTwo->id,
            'participant_type' => get_class($userTwo),
        ]);
        QuizAttemptAnswer::create([
            'quiz_attempt_id' => $attempt->id,
            'quiz_question_id' => $quiz_question_one->id,
            'question_option_id' => $question_one->options()->first()->id,
            'answer' => 'Doe'
        ]);
        QuizAttemptAnswer::create([
            'quiz_attempt_id' => $attempt->id,
            'quiz_question_id' => $quiz_question_two->id,
            'question_option_id' => $quiz_question_two->question->options()->first()->id,
            'answer' => 'John'
        ]);
        $this->assertEquals(2, $attempt->answers()->count());
        $this->assertEquals(1, $quiz_question_one->answers()->count());
        $this->assertEquals(1, $quiz_question_two->answers()->count());
        $this->assertEquals('Doe', $quiz_question_one->answers()->first()->answer);
        $this->assertEquals('John', $quiz_question_two->answers()->first()->answer);
        $this->assertEquals(1, $question_one->options()->first()->answers()->count());
        $this->assertEquals(1, $question_two->options()->first()->answers()->count());
    }
}
