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

    function init($questionType = 1, $enableNegativeMarks = true, $negativeMarkingType = 'fixed', $quizNegativemarkValue = 0, $marks = 1, $negativeMarks = 0)
    {
        $user = Author::create(
            ['name' => "John Doe"]
        );
        $options = [];
        //Question Types
        QuestionType::insert(
            [
                [
                    'name' => 'multiple_choice_single_answer',
                ],
                [
                    'name' => 'multiple_choice_multiple_answer',
                ],
                [
                    'name' => 'fill_the_blank',
                ]
            ]
        );
        if ($questionType == 1) {
            $question = Question::factory()->create([
                'name' => 'How many layers in OSI model?',
                'question_type_id' => 1,
                'is_active' => false,
            ]);
            $question_option_one = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => '5',
                'is_correct' => false,
            ]);
            $question_option_two = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => '8',
                'is_correct' => false,
            ]);
            $question_option_three = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => '10',
                'is_correct' => false,
            ]);
            $question_option_four = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => '7',
                'is_correct' => true,
            ]);
            $options = [$question_option_one, $question_option_two, $question_option_three, $question_option_four];
        } elseif ($questionType == 2) {
            $question = Question::factory()->create([
                'name' => 'Which of the below is a data structure?',
                'question_type_id' => 2,
                'is_active' => true,
            ]);
            $question_option_one = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => 'array',
                'is_correct' => true,
            ]);
            $question_option_two = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => 'object',
                'is_correct' => true,
            ]);
            $question_option_three = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => 'for loop',
                'is_correct' => false,
            ]);
            $question_option_four = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => 'method',
                'is_correct' => false,
            ]);
            $options = [$question_option_one, $question_option_two, $question_option_three, $question_option_four];
        } else {
            $question = Question::factory()->create([
                'name' => 'Full Form Of CPU',
                'question_type_id' => 3,
                'is_active' => true,
            ]);
            $question_option_one = QuestionOption::factory()->create([
                'question_id' => $question->id,
                'name' => 'central processing unit',
                'is_correct' => true,
            ]);
            $options = [$question_option_one];
        }
        $quiz = Quiz::factory()->make()->create([
            'name' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => $enableNegativeMarks,
                'negative_marking_type' => $negativeMarkingType,
                'negative_mark_value' => $quizNegativemarkValue,
            ]
        ]);
        $quizQuestion =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => $marks,
            'order' => 1,
            'negative_marks' => $negativeMarks,
        ]);
        $quizAttempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user)
        ]);
        return [$user, $question, $options, $quiz, $quizQuestion, $quizAttempt];
    }

    /** @test */
    function get_score_for_type_1_question_no_negative_marks()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(1, false, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 5, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;
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
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(1, true, Quiz::FIXED_NEGATIVE_TYPE, 0, 5, 1);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;
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
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(1, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 5, 10);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;
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
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(1, true, Quiz::FIXED_NEGATIVE_TYPE, 2, 10, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

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
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(1, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 5, 10, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;
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
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, false, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 8, 2);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

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


        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::FIXED_NEGATIVE_TYPE, 0, 8, 2);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-2, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_2_question_with_negative_marks_question_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 8, 2);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-0.16, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_2_question_with_negative_marks_quiz_fixed()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::FIXED_NEGATIVE_TYPE, 1, 5, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_2_question_with_negative_marks_quiz_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 10, 5, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-0.5, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_3_question_no_negative_marks()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, false, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 5, 0);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'central processing unit'
            ]
        );
        $this->assertEquals(5, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_3_question_with_negative_marks_question_fixed()
    {


        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::FIXED_NEGATIVE_TYPE, 0, 5, 1);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu'
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_3_question_with_negative_marks_question_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 10, 10);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu'
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_3_question_with_negative_marks_quiz_fixed()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::FIXED_NEGATIVE_TYPE, 1, 5, 0);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu'
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_score_for_type_3_question_with_negative_marks_quiz_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 10, 5, 0);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu'
            ]
        );
        $this->assertEquals(-0.5, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    /** @test */
    function get_negative_marks_for_question()
    {
        $testCases = [
            [
                'enable_negative_marks' => false,
                'quiz_negative_marks_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'quiz_negative_marks' => 0,
                'question_marks' => 5,
                'question_negative_marks' => 0,
                'expected_negative_marks' => 0
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'quiz_negative_marks' => 0,
                'question_marks' => 5,
                'question_negative_marks' => 1,
                'expected_negative_marks' => 1
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::PERCENTAGE_NEGATIVE_TYPE,
                'quiz_negative_marks' => 0,
                'question_marks' => 5,
                'question_negative_marks' => 30,
                'expected_negative_marks' => 1.5
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'quiz_negative_marks' => 1,
                'question_marks' => 5,
                'question_negative_marks' => 0,
                'expected_negative_marks' => 1
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::PERCENTAGE_NEGATIVE_TYPE,
                'quiz_negative_marks' => 40,
                'question_marks' => 5,
                'question_negative_marks' => 0,
                'expected_negative_marks' => 2
            ]
        ];
        $question = Question::factory()->create([
            'name' => 'Full Form Of CPU',
            'question_type_id' => 3,
            'is_active' => true,
        ]);
        foreach ($testCases as $key => $testCase) {
            $quiz = Quiz::factory()->make()->create([
                'name' => 'Sample Quiz',
                'slug' => 'sample-quiz-' . $key,
                'negative_marking_settings' => [
                    'enable_negative_marks' => $testCase['enable_negative_marks'],
                    'negative_marking_type' => $testCase['quiz_negative_marks_type'],
                    'negative_mark_value' => $testCase['quiz_negative_marks'],
                ]
            ]);
            $quizQuestion =  QuizQuestion::factory()->create([
                'quiz_id' => $quiz->id,
                'question_id' => $question->id,
                'marks' => $testCase['question_marks'],
                'order' => 1,
                'negative_marks' => $testCase['question_negative_marks'],
            ]);
            $this->assertEquals($testCase['expected_negative_marks'], QuizAttempt::get_negative_marks_for_question($quiz, $quizQuestion));
        }
    }

    /** @test */
    function get_quiz_attempt_result_of_single_question(){
        $testCases = [
           [
               'name' => 'Correct Answer'
           ]
        ];
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, false, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 5, 0);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one =  QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'central processing unit'
            ]
        );
        print_r(json_encode($quiz_attempt->validate($quiz_question->id)));
        $this->assertEquals([1=>['score'=>5,'is_correct'=>true]],$quiz_attempt->validate($quiz_question->id));
    }
}
