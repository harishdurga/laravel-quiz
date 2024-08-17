<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Harishdurga\LaravelQuiz\Models\QuizAttemptAnswer;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Tests\Models\Author;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;

class QuizAttemptTest extends TestCase
{
    use RefreshDatabase;

    public function init($questionType = 1, $enableNegativeMarks = true, $negativeMarkingType = 'fixed', $quizNegativemarkValue = 0, $marks = 1, $negativeMarks = 0)
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
                ],
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
            ],
        ]);
        $quizQuestion = QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => $marks,
            'order' => 1,
            'negative_marks' => $negativeMarks,
        ]);
        $quizAttempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user),
        ]);
        return [$user, $question, $options, $quiz, $quizQuestion, $quizAttempt];
    }

    #[Test]
    public function get_score_for_type_1_question_no_negative_marks()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(1, false, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 5, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;
        //Quiz Attempt And Answers
        $quiz_attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user),
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

    #[Test]
    public function get_score_for_type_1_question_with_negative_marks_question_fixed()
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

    #[Test]
    public function get_score_for_type_1_question_with_negative_marks_question_percentage()
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

    #[Test]
    public function get_score_for_type_1_question_with_negative_marks_quiz_fixed()
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

    #[Test]
    public function get_score_for_type_1_question_with_negative_marks_quiz_percentage()
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

    #[Test]
    public function get_score_for_type_2_question_no_negative_marks()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, false, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 8, 2);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
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

    #[Test]
    public function get_score_for_type_2_question_with_negative_marks_question_fixed()
    {

        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::FIXED_NEGATIVE_TYPE, 0, 8, 2);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-2, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_2_question_with_negative_marks_question_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 8, 2);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-0.16, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_2_question_with_negative_marks_quiz_fixed()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::FIXED_NEGATIVE_TYPE, 1, 5, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_2_question_with_negative_marks_quiz_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(2, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 10, 5, 0);
        [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
            ]
        );
        $this->assertEquals(-0.5, QuizAttempt::get_score_for_type_2_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_3_question_no_negative_marks()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, false, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 5, 0);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'central processing unit',
            ]
        );
        $this->assertEquals(5, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_3_question_with_negative_marks_question_fixed()
    {

        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::FIXED_NEGATIVE_TYPE, 0, 5, 1);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu',
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_3_question_with_negative_marks_question_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 0, 10, 10);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu',
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_3_question_with_negative_marks_quiz_fixed()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::FIXED_NEGATIVE_TYPE, 1, 5, 0);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu',
            ]
        );
        $this->assertEquals(-1, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_score_for_type_3_question_with_negative_marks_quiz_percentage()
    {
        [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init(3, true, Quiz::PERCENTAGE_NEGATIVE_TYPE, 10, 5, 0);
        [$question_option_one] = $options;

        $quiz_attempt_answer_one = QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt->id,
                'quiz_question_id' => $quiz_question->id,
                'question_option_id' => $question_option_one->id,
                'answer' => 'cpu',
            ]
        );
        $this->assertEquals(-0.5, QuizAttempt::get_score_for_type_3_question($quiz_attempt, $quiz_question, [$quiz_attempt_answer_one]));
    }

    #[Test]
    public function get_negative_marks_for_question()
    {
        $testCases = [
            [
                'enable_negative_marks' => false,
                'quiz_negative_marks_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'quiz_negative_marks' => 0,
                'question_marks' => 5,
                'question_negative_marks' => 0,
                'expected_negative_marks' => 0,
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'quiz_negative_marks' => 0,
                'question_marks' => 5,
                'question_negative_marks' => 1,
                'expected_negative_marks' => 1,
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::PERCENTAGE_NEGATIVE_TYPE,
                'quiz_negative_marks' => 0,
                'question_marks' => 5,
                'question_negative_marks' => 30,
                'expected_negative_marks' => 1.5,
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'quiz_negative_marks' => 1,
                'question_marks' => 5,
                'question_negative_marks' => 0,
                'expected_negative_marks' => 1,
            ],
            [
                'enable_negative_marks' => true,
                'quiz_negative_marks_type' => Quiz::PERCENTAGE_NEGATIVE_TYPE,
                'quiz_negative_marks' => 40,
                'question_marks' => 5,
                'question_negative_marks' => 0,
                'expected_negative_marks' => 2,
            ],
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
                ],
            ]);
            $quizQuestion = QuizQuestion::factory()->create([
                'quiz_id' => $quiz->id,
                'question_id' => $question->id,
                'marks' => $testCase['question_marks'],
                'order' => 1,
                'negative_marks' => $testCase['question_negative_marks'],
            ]);
            $this->assertEquals($testCase['expected_negative_marks'], QuizAttempt::get_negative_marks_for_question($quiz, $quizQuestion));
        }
    }

    #[Test]
    public function get_quiz_attempt_result_with_and_without_quiz_question()
    {

        $testCases = [
            [
                'name' => 'Question type 1 with no negative marks',
                'question_type' => 1,
                'enable_negative_marks' => false,
                'negative_marking_type' => Quiz::PERCENTAGE_NEGATIVE_TYPE,
                'quiz_negative_mark_value' => 0,
                'marks' => 5,
                'negative_marks' => 0,
                'expected' => [1 => ['score' => 5, 'is_correct' => true, 'correct_answer' => '7', 'user_answer' => 7]],
            ],
            [
                'name' => 'Question type 2 with no negative marks',
                'question_type' => 2,
                'enable_negative_marks' => false,
                'negative_marking_type' => Quiz::PERCENTAGE_NEGATIVE_TYPE,
                'quiz_negative_mark_value' => 0,
                'marks' => 5,
                'negative_marks' => 0,
                'expected' => [1 => ['score' => 5, 'is_correct' => true, 'correct_answer' => ['array', 'object'], 'user_answer' => ['array', 'object']]],
            ],
            [
                'name' => 'Question type 3 with no negative marks',
                'question_type' => 3,
                'enable_negative_marks' => false,
                'negative_marking_type' => Quiz::PERCENTAGE_NEGATIVE_TYPE,
                'quiz_negative_mark_value' => 0,
                'marks' => 5,
                'negative_marks' => 0,
                'expected' => [1 => ['score' => 5, 'is_correct' => true, 'correct_answer' => 'central processing unit', 'user_answer' => 'central processing unit']],
            ],
        ];
        foreach ($testCases as $testCase) {
            [$user, $question, $options, $quiz, $quiz_question, $quiz_attempt] = $this->init($testCase['question_type'], $testCase['enable_negative_marks'], $testCase['negative_marking_type'], $testCase['quiz_negative_mark_value'], $testCase['marks'], $testCase['negative_marks']);
            if ($testCase['question_type'] == 1 || $testCase['question_type'] == 2) {
                [$question_option_one, $question_option_two, $question_option_three, $question_option_four] = $options;
            } else {
                [$question_option_one] = $options;
            }
            if ($testCase['question_type'] == 1) {
                QuizAttemptAnswer::create(
                    [
                        'quiz_attempt_id' => $quiz_attempt->id,
                        'quiz_question_id' => $quiz_question->id,
                        'question_option_id' => $question_option_four->id,
                    ]
                );
            } elseif ($testCase['question_type'] == 2) {
                QuizAttemptAnswer::create(
                    [
                        'quiz_attempt_id' => $quiz_attempt->id,
                        'quiz_question_id' => $quiz_question->id,
                        'question_option_id' => $question_option_one->id,
                    ]
                );
                QuizAttemptAnswer::create(
                    [
                        'quiz_attempt_id' => $quiz_attempt->id,
                        'quiz_question_id' => $quiz_question->id,
                        'question_option_id' => $question_option_two->id,
                    ]
                );
            } else {
                QuizAttemptAnswer::create(
                    [
                        'quiz_attempt_id' => $quiz_attempt->id,
                        'quiz_question_id' => $quiz_question->id,
                        'question_option_id' => $question_option_one->id,
                        'answer' => 'central processing unit',
                    ]
                );
            }
            $this->assertEquals($testCase['expected'], $quiz_attempt->validate($quiz_question->id));
            $this->assertEquals($testCase['expected'], $quiz_attempt->validate());
            DB::raw("SET foreign_key_checks=0");
            $databaseName = DB::getDatabaseName();
            $tables = config('laravel-quiz.table_names');
            foreach ($tables as $key => $name) {
                //if you don't want to truncate migrations
                if ($name == 'migrations') {
                    continue;
                }
                DB::table($name)->truncate();
            }
            DB::raw("SET foreign_key_checks=1");
        }
    }

    #[Test]
    public function test_quiz_attempt_validate()
    {

        $user = Author::create(
            ['name' => "John Doe"]
        );
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
                ],
            ]
        );
        $questionsWithOptions = [
            [
                'question' => 'How many world wonders are there?',
                'options' => [
                    [1, 7, true],
                ],
                'id' => 1,
                'question_type' => 3,
            ],
            [
                'question' => 'What is the biggest desert in the world?',
                'options' => [[2, 'Sahara', true]],
                'id' => 2,
                'question_type' => 3,
            ],
            [
                'question' => 'What is the biggest bird?',
                'options' => [[3, 'Ostrich', true]],
                'id' => 3,
                'question_type' => 3,
            ],
            [
                'question' => 'Which One of these is not a continent?',
                'options' => [
                    [4, 'US', true], [5, 'Asia', false], [6, 'Europe', false], [7, 'Australia', false],
                ],
                'id' => 4,
                'question_type' => 1,
            ],
            [
                'question' => 'Which of the following is a non metal that remains liquid at room temperature?',
                'options' => [
                    [8, 'Phosphorous', false], [9, 'Bromine', true], [10, 'Chlorine', false], [11, 'Helium', false],
                ],
                'id' => 5,
                'question_type' => 1,
            ],
            [
                'question' => 'Select All The Mammals',
                'options' => [
                    [12, 'cats', true], [13, 'Dogs', true], [14, 'apes', true], [15, 'None of the above', false],
                ],
                'id' => 6,
                'question_type' => 2,
            ],
            [
                'question' => 'Select All The Amphibians',
                'options' => [
                    [16, 'frogs', true], [17, 'Dogs', false], [18, 'salamanders', true], [19, 'None of the above', false],
                ],
                'id' => 7,
                'question_type' => 2,
            ],

        ];
        $quiz = Quiz::factory()->make()->create([
            'name' => 'Sample Quiz',
            'slug' => 'sample-quiz',
            'negative_marking_settings' => [
                'enable_negative_marks' => false,
                'negative_marking_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'negative_mark_value' => 1,
            ],
        ]);
        $quizAttemptOne = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user),
        ]);
        $quizAttemptTwo = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $user->id,
            'participant_type' => get_class($user),
        ]);
        foreach ($questionsWithOptions as $questionsWithOption) {
            Question::factory()->create([
                'name' => $questionsWithOption['question'],
                'question_type_id' => $questionsWithOption['question_type'],
                'is_active' => true,
                'id' => $questionsWithOption['id'],
            ]);
            foreach ($questionsWithOption['options'] as $option) {
                QuestionOption::factory()->create([
                    'question_id' => $questionsWithOption['id'],
                    'name' => $option[1],
                    'is_correct' => $option[2],
                    'id' => $option[0],
                ]);
            }

            QuizQuestion::factory()->create([
                'quiz_id' => $quiz->id,
                'question_id' => $questionsWithOption['id'],
                'marks' => 1,
                'order' => 1,
                'negative_marks' => 0.5,
                'id' => $questionsWithOption['id'],
            ]);
            foreach ($questionsWithOption['options'] as $option) {
                if ($option[2]) {
                    QuizAttemptAnswer::create(
                        [
                            'quiz_attempt_id' => $quizAttemptOne->id,
                            'quiz_question_id' => $questionsWithOption['id'],
                            'question_option_id' => $option[0],
                            'answer' => $option[1],
                        ]
                    );
                    if ($questionsWithOption['id'] != 3) { //Skipping the third question being attempted
                        QuizAttemptAnswer::create(
                            [
                                'quiz_attempt_id' => $quizAttemptTwo->id,
                                'quiz_question_id' => $questionsWithOption['id'],
                                'question_option_id' => $option[0],
                                'answer' => $option[1] . 's',
                            ]
                        );
                    }

                }

            }

        }

        $this->assertEquals([
            1 => [
                'score' => 1.0,
                "is_correct" => true,
                'correct_answer' => 7,
                'user_answer' => 7,
            ],

            2 => [
                'score' => 1.0,
                'is_correct' => true,
                'correct_answer' => 'Sahara',
                'user_answer' => 'Sahara',
            ],

            3 => [
                'score' => 1.0,
                'is_correct' => true,
                'correct_answer' => 'Ostrich',
                'user_answer' => 'Ostrich',
            ],

            4 => [

                'score' => 1,
                'is_correct' => true,
                'correct_answer' => 'US',
                'user_answer' => 'US',
            ],

            5 => [
                'score' => 1,
                'is_correct' => true,
                'correct_answer' => 'Bromine',
                'user_answer' => 'Bromine',
            ],

            6 => [
                'score' => 1,
                'is_correct' => true,
                'correct_answer' => [
                    0 => 'cats',
                    1 => 'Dogs',
                    2 => 'apes',
                ],

                'user_answer' => [
                    0 => 'cats',
                    1 => 'Dogs',
                    2 => 'apes',
                ],

            ],

            7 => [
                'score' => 1,
                'is_correct' => true,
                'correct_answer' => [
                    0 => 'frogs',
                    1 => 'salamanders',
                ],

                'user_answer' => [
                    0 => 'frogs',
                    1 => 'salamanders',
                ],
            ],

        ], $quizAttemptOne->validate(), 'Quiz Attempt With Correct Answers');

        $this->assertEquals(array(
            1 => array(
                'score' => -0,
                'is_correct' => false,
                'correct_answer' => 7,
                'user_answer' => '7s',
            ),
            2 => array(
                'score' => -0,
                'is_correct' => false,
                'correct_answer' => 'Sahara',
                'user_answer' => 'Saharas',
            ),
            3 => array(
                'score' => -0,
                'is_correct' => false,
                'correct_answer' => 'Ostrich',
                'user_answer' => '',
            ),
            4 => array(
                'score' => 1,
                'is_correct' => true,
                'correct_answer' => 'US',
                'user_answer' => 'US',
            ),
            5 => array(
                'score' => 1,
                'is_correct' => true,
                'correct_answer' => 'Bromine',
                'user_answer' => 'Bromine',
            ),
            6 => array(
                'score' => 1,
                'is_correct' => true,
                'correct_answer' => array(
                    0 => 'cats',
                    1 => 'Dogs',
                    2 => 'apes',
                ),
                'user_answer' => array(
                    0 => 'cats',
                    1 => 'Dogs',
                    2 => 'apes',
                ),
            ),
            7 => array(
                'score' => 1,
                'is_correct' => true,
                'correct_answer' => array(
                    0 => 'frogs',
                    1 => 'salamanders',
                ),
                'user_answer' => array(
                    0 => 'frogs',
                    1 => 'salamanders',
                ),
            ),
        ), $quizAttemptTwo->validate(), 'Quiz Attempt With Wrong Answers');

    }
}
