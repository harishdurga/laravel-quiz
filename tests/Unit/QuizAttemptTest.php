<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Tests\Models\Author;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Harishdurga\LaravelQuiz\Models\QuizAttemptAnswer;


class QuizAttemptTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function get_score_for_type_1_question_no_negative_marks()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        //Question Types
        QuestionType::insert(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
        //Question And Options
        $question = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_option_one = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_option_two = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_option_three = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_option_four = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => false,
                'negative_marking_type' => 'fixed',
                'negative_mark_value' => 0,
            ]
        ]);
        $quiz_question =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 5,
            'order' => 1,
            'negative_marks' => 1,
        ]);
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt_answer = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_four->id,
            ]
        );
        $this->assertEquals(5, QuizAttempt::get_score_for_type_1_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer]));
    }

    /** @test */
    function get_score_for_type_1_question_with_negative_marks_question_fixed()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        //Question Types
        QuestionType::insert(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
        //Question And Options
        $question = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_option_one = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_option_two = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_option_three = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_option_four = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => true,
                'negative_marking_type' => 'fixed',
                'negative_mark_value' => 0,
            ]
        ]);
        $quiz_question =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 5,
            'order' => 1,
            'negative_marks' => 1,
        ]);
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt_answer = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_three->id,
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_1_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer]));
    }

    /** @test */
    function get_score_for_type_1_question_with_negative_marks_question_percentage()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        //Question Types
        QuestionType::insert(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
        //Question And Options
        $question = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_option_one = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_option_two = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_option_three = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_option_four = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => true,
                'negative_marking_type' => 'percentage',
                'negative_mark_value' => 0,
            ]
        ]);
        $quiz_question =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 5,
            'order' => 1,
            'negative_marks' => 10,
        ]);
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt_answer = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_three->id,
            ]
        );
        $this->assertEquals(-0.5, QuizAttempt::get_score_for_type_1_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer]));
    }

    /** @test */
    function get_score_for_type_1_question_with_negative_marks_quiz_fixed()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        //Question Types
        QuestionType::insert(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
        //Question And Options
        $question = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_option_one = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_option_two = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_option_three = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_option_four = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => true,
                'negative_marking_type' => 'fixed',
                'negative_mark_value' => 2,
            ]
        ]);
        $quiz_question =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 10,
            'order' => 1,
            'negative_marks' => 0,
        ]);
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt_answer = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_three->id,
            ]
        );
        $this->assertEquals(-2, QuizAttempt::get_score_for_type_1_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer]));
    }

    /** @test */
    function get_score_for_type_1_question_with_negative_marks_quiz_percentage()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        //Question Types
        QuestionType::insert(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
        //Question And Options
        $question = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_option_one = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_option_two = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_option_three = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_option_four = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => true,
                'negative_marking_type' => 'percentage',
                'negative_mark_value' => 5,
            ]
        ]);
        $quiz_question =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 10,
            'order' => 1,
            'negative_marks' => 0,
        ]);
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt_answer = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_three->id,
            ]
        );
        $this->assertEquals(-0.5, QuizAttempt::get_score_for_type_1_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer]));
    }

    /** @test */
    function get_score_for_type_2_question_no_negative_marks()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        //Question Types
        QuestionType::insert(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
        //Question And Options
        $question = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 2,
            'is_active' => true,
        ]);

        $question_option_one = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_option_two = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'object',
            'is_correct' => true,
        ]);
        $question_option_three = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'for loop',
            'is_correct' => false,
        ]);
        $question_option_four = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => false,
                'negative_marking_type' => 'fixed',
                'negative_mark_value' => 0,
            ]
        ]);
        $quiz_question =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 8,
            'order' => 1,
            'negative_marks' => 2,
        ]);
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $quiz_attempt_answer_two = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_two->id,
            ]
        );
        $this->assertEquals(8, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one, $quiz_attempt_answer_two]));
    }

    /** @test */
    function get_score_for_type_2_question_with_negative_marks_question_fixed()
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        //Question Types
        QuestionType::insert(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
        //Question And Options
        $question = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 2,
            'is_active' => true,
        ]);

        $question_option_one = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_option_two = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'object',
            'is_correct' => true,
        ]);
        $question_option_three = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'for loop',
            'is_correct' => false,
        ]);
        $question_option_four = QuestionOption::factory()->create([
            'question_id' => $question->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $quiz = Quiz::factory()->make()->create([
            'title' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => false,
                'negative_marking_type' => 'fixed',
                'negative_mark_value' => 0,
            ]
        ]);
        $quiz_question =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 8,
            'order' => 1,
            'negative_marks' => 2,
        ]);
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        // $quiz_attempt_answer_two = QuizAttemptAnswer::create(
        //     [
        //         'quiz_attempt_id' => $quiz_attempt->id,
        //         'quiz_question_id' => $quiz_question->id,
        //         'question_option_id' => $question_option_two->id,
        //     ]
        // );
        $this->assertEquals(-2, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }
}
