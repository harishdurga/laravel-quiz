<?php

namespace Harishdurga\LaravelQuiz\Tests\Feature;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuizAttempt;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Models\QuizQuestion;
use Harishdurga\LaravelQuiz\Tests\Models\Author;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Harishdurga\LaravelQuiz\Models\QuizAttemptAnswer;

class QuizTest extends TestCase
{
    use RefreshDatabase;


    /** @test- */
    function quiz_multiple_choice_single_answer_all_correct_answers()
    {
        $computer_science = Topic::factory()->create([
            'topic' => 'Computer Science',
            'slug' => 'computer-science',
        ]);
        $algorithms = Topic::factory()->create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $data_structures = Topic::factory()->create([
            'topic' => 'Data Structures',
            'slug' => 'data-structures'
        ]);
        $computer_networks = Topic::factory()->create([
            'topic' => 'Computer Networks',
            'slug' => 'computer-networks'
        ]);
        $computer_science->children()->save($algorithms);
        $computer_science->children()->save($data_structures);
        $computer_science->children()->save($computer_networks);
        $this->assertEquals(3, $computer_science->children()->count());

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

        //Question One And Options
        $question_one = Question::factory()->create([
            'question' => 'What is an algorithm?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);
        $question_one_option_one = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A computer program that solves a problem.',
            'is_correct' => false,
        ]);
        $question_one_option_two = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of rules that define the behavior of a computer program.',
            'is_correct' => false,
        ]);
        $question_one_option_three = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of instructions that tell a computer what to do.',
            'is_correct' => true,
        ]);
        $question_one_option_four = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'None of the above.',
            'is_correct' => false,
        ]);
        $question_one->topics()->attach([$computer_science->id, $algorithms->id]);
        $this->assertEquals(2, $question_one->topics->count());
        $this->assertEquals(4, $question_one->options->count());

        //Question Two And Options
        $question_two = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);

        $question_two_option_one = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_two_option_two = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'if',
            'is_correct' => false,
        ]);
        $question_two_option_three = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'for loop',
            'is_correct' => false,
        ]);
        $question_two_option_four = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $question_two->topics()->attach([$computer_science->id, $data_structures->id]);
        $this->assertEquals(2, $question_two->topics->count());
        $this->assertEquals(4, $question_two->options->count());

        //Question Three And Options
        $question_three = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_three_option_one = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_three_option_four = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $question_three->topics()->attach([$computer_science->id, $computer_networks->id]);
        $this->assertEquals(2, $question_three->topics->count());
        $this->assertEquals(4, $question_three->options->count());

        $this->assertEquals(3, $computer_science->questions()->count());

        //Quiz
        $quiz = Quiz::factory()->create([
            'title' => 'Computer Sceince Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0,
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'time_between_attempts' => 0,
        ]);

        //Add Question to Quiz
        $quiz_question_one =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
            'marks' => 3,
            'order' => 1,
        ]);
        $quiz_question_two =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
            'marks' => 3,
            'order' => 2,
        ]);
        $quiz_question_three =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_three->id,
            'marks' => 4,
            'order' => 2,
            'negative_marks' => 2,
        ]);

        $this->assertEquals(3, $quiz->questions->count());
        $this->assertEquals(10, $quiz->questions->sum('marks'));

        //Participants
        $participant_one = Author::create([
            'name' => 'Bravo'
        ]);
        $participant_two = Author::create([
            'name' => 'Charlie'
        ]);

        //Quiz Attempt One And Answers
        $quiz_attempt_one = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_one->id,
            'participant_type' => get_class($participant_one)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_three->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_three->id,
                'question_option_id' => $question_three_option_four->id,
            ]
        );

        $this->assertEquals(3, $quiz_attempt_one->answers->count());
        //Calculate Obtained marks
        $this->assertEquals(10, $quiz_attempt_one->calculate_score());
    }

    /** @test- */
    function quiz_multiple_choice_single_answer_few_worng_answers()
    {
        $computer_science = Topic::factory()->create([
            'topic' => 'Computer Science',
            'slug' => 'computer-science',
        ]);
        $algorithms = Topic::factory()->create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $data_structures = Topic::factory()->create([
            'topic' => 'Data Structures',
            'slug' => 'data-structures'
        ]);
        $computer_networks = Topic::factory()->create([
            'topic' => 'Computer Networks',
            'slug' => 'computer-networks'
        ]);
        $computer_science->children()->save($algorithms);
        $computer_science->children()->save($data_structures);
        $computer_science->children()->save($computer_networks);
        $this->assertEquals(3, $computer_science->children()->count());

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

        //Question One And Options
        $question_one = Question::factory()->create([
            'question' => 'What is an algorithm?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);
        $question_one_option_one = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A computer program that solves a problem.',
            'is_correct' => false,
        ]);
        $question_one_option_two = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of rules that define the behavior of a computer program.',
            'is_correct' => false,
        ]);
        $question_one_option_three = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of instructions that tell a computer what to do.',
            'is_correct' => true,
        ]);
        $question_one_option_four = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'None of the above.',
            'is_correct' => false,
        ]);
        $question_one->topics()->attach([$computer_science->id, $algorithms->id]);
        $this->assertEquals(2, $question_one->topics->count());
        $this->assertEquals(4, $question_one->options->count());

        //Question Two And Options
        $question_two = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);

        $question_two_option_one = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_two_option_two = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'if',
            'is_correct' => false,
        ]);
        $question_two_option_three = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'for loop',
            'is_correct' => false,
        ]);
        $question_two_option_four = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $question_two->topics()->attach([$computer_science->id, $data_structures->id]);
        $this->assertEquals(2, $question_two->topics->count());
        $this->assertEquals(4, $question_two->options->count());

        //Question Three And Options
        $question_three = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_three_option_one = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_three_option_four = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $question_three->topics()->attach([$computer_science->id, $computer_networks->id]);
        $this->assertEquals(2, $question_three->topics->count());
        $this->assertEquals(4, $question_three->options->count());

        $this->assertEquals(3, $computer_science->questions()->count());

        //Quiz
        $quiz = Quiz::factory()->create([
            'title' => 'Computer Sceince Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0,
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'time_between_attempts' => 0,
        ]);

        //Add Question to Quiz
        $quiz_question_one =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
            'marks' => 3,
            'order' => 1,
        ]);
        $quiz_question_two =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
            'marks' => 3,
            'order' => 2,
        ]);
        $quiz_question_three =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_three->id,
            'marks' => 4,
            'order' => 2,
            'negative_marks' => 2,
        ]);

        $this->assertEquals(3, $quiz->questions->count());
        $this->assertEquals(10, $quiz->questions->sum('marks'));

        //Participants
        $participant_one = Author::create([
            'name' => 'Bravo'
        ]);
        $participant_two = Author::create([
            'name' => 'Charlie'
        ]);

        //Quiz Attempt One And Answers
        $quiz_attempt_one = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_one->id,
            'participant_type' => get_class($participant_one)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_three->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_three->id,
                'question_option_id' => $question_three_option_three->id,
            ]
        );

        $this->assertEquals(3, $quiz_attempt_one->answers->count());
        //Calculate Obtained marks
        $this->assertEquals(4, $quiz_attempt_one->calculate_score());
    }

    /** @test- */
    function quiz_multiple_choice_multiple_answer_all_correct_answers()
    {
        $computer_science = Topic::factory()->create([
            'topic' => 'Computer Science',
            'slug' => 'computer-science',
        ]);
        $algorithms = Topic::factory()->create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $data_structures = Topic::factory()->create([
            'topic' => 'Data Structures',
            'slug' => 'data-structures'
        ]);
        $computer_networks = Topic::factory()->create([
            'topic' => 'Computer Networks',
            'slug' => 'computer-networks'
        ]);
        $computer_science->children()->save($algorithms);
        $computer_science->children()->save($data_structures);
        $computer_science->children()->save($computer_networks);
        $this->assertEquals(3, $computer_science->children()->count());

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

        //Question One And Options
        $question_one = Question::factory()->create([
            'question' => 'What is an algorithm?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);
        $question_one_option_one = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A computer program that solves a problem.',
            'is_correct' => false,
        ]);
        $question_one_option_two = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of rules that define the behavior of a computer program.',
            'is_correct' => false,
        ]);
        $question_one_option_three = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of instructions that tell a computer what to do.',
            'is_correct' => true,
        ]);
        $question_one_option_four = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'None of the above.',
            'is_correct' => false,
        ]);
        $question_one->topics()->attach([$computer_science->id, $algorithms->id]);
        $this->assertEquals(2, $question_one->topics->count());
        $this->assertEquals(4, $question_one->options->count());

        //Question Two And Options
        $question_two = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);

        $question_two_option_one = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_two_option_two = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'string',
            'is_correct' => true,
        ]);
        $question_two_option_three = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'object',
            'is_correct' => true,
        ]);
        $question_two_option_four = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $question_two->topics()->attach([$computer_science->id, $data_structures->id]);
        $this->assertEquals(2, $question_two->topics->count());
        $this->assertEquals(4, $question_two->options->count());

        //Question Three And Options
        $question_three = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_three_option_one = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_three_option_four = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $question_three->topics()->attach([$computer_science->id, $computer_networks->id]);
        $this->assertEquals(2, $question_three->topics->count());
        $this->assertEquals(4, $question_three->options->count());

        $this->assertEquals(3, $computer_science->questions()->count());

        //Quiz
        $quiz = Quiz::factory()->create([
            'title' => 'Computer Sceince Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0,
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'time_between_attempts' => 0,
        ]);

        //Add Question to Quiz
        $quiz_question_one =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
            'marks' => 3,
            'order' => 1,
        ]);
        $quiz_question_two =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
            'marks' => 3,
            'order' => 2,
            'negative_marks' => 0
        ]);
        $quiz_question_three =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_three->id,
            'marks' => 4,
            'order' => 3,
            'negative_marks' => 2,
        ]);

        $this->assertEquals(3, $quiz->questions->count());
        $this->assertEquals(10, $quiz->questions->sum('marks'));

        //Participants
        $participant_one = Author::create([
            'name' => 'Bravo'
        ]);

        //Quiz Attempt One And Answers
        $quiz_attempt_one = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_one->id,
            'participant_type' => get_class($participant_one)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_three->id,
            ]
        );


        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_two->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_three->id,
            ]
        );


        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_three->id,
                'question_option_id' => $question_three_option_four->id,
            ]
        );

        //Calculate Obtained marks
        $this->assertEquals(10, $quiz_attempt_one->calculate_score());
    }

    /** @test- */
    function quiz_multiple_choice_multiple_answer_all_few_wrong_answers()
    {
        $computer_science = Topic::factory()->create([
            'topic' => 'Computer Science',
            'slug' => 'computer-science',
        ]);
        $algorithms = Topic::factory()->create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $data_structures = Topic::factory()->create([
            'topic' => 'Data Structures',
            'slug' => 'data-structures'
        ]);
        $computer_networks = Topic::factory()->create([
            'topic' => 'Computer Networks',
            'slug' => 'computer-networks'
        ]);
        $computer_science->children()->save($algorithms);
        $computer_science->children()->save($data_structures);
        $computer_science->children()->save($computer_networks);
        $this->assertEquals(3, $computer_science->children()->count());

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

        //Question One And Options
        $question_one = Question::factory()->create([
            'question' => 'What is an algorithm?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);
        $question_one_option_one = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A computer program that solves a problem.',
            'is_correct' => false,
        ]);
        $question_one_option_two = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of rules that define the behavior of a computer program.',
            'is_correct' => false,
        ]);
        $question_one_option_three = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'A set of instructions that tell a computer what to do.',
            'is_correct' => true,
        ]);
        $question_one_option_four = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'None of the above.',
            'is_correct' => false,
        ]);
        $question_one->topics()->attach([$computer_science->id, $algorithms->id]);
        $this->assertEquals(2, $question_one->topics->count());
        $this->assertEquals(4, $question_one->options->count());

        //Question Two And Options
        $question_two = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 2,
            'is_active' => true,
        ]);

        $question_two_option_one = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_two_option_two = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'string',
            'is_correct' => true,
        ]);
        $question_two_option_three = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'object',
            'is_correct' => true,
        ]);
        $question_two_option_four = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $question_two->topics()->attach([$computer_science->id, $data_structures->id]);
        $this->assertEquals(2, $question_two->topics->count());
        $this->assertEquals(4, $question_two->options->count());

        //Question Three And Options
        $question_three = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_three_option_one = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_three_option_four = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $question_three->topics()->attach([$computer_science->id, $computer_networks->id]);
        $this->assertEquals(2, $question_three->topics->count());
        $this->assertEquals(4, $question_three->options->count());

        $this->assertEquals(3, $computer_science->questions()->count());

        //Quiz
        $quiz = Quiz::factory()->create([
            'title' => 'Computer Sceince Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0,
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'time_between_attempts' => 0,
        ]);

        //Add Question to Quiz
        $quiz_question_one =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
            'marks' => 3,
            'order' => 1,
        ]);
        $quiz_question_two =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
            'marks' => 3,
            'order' => 2,
            'negative_marks' => 0
        ]);
        $quiz_question_three =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_three->id,
            'marks' => 4,
            'order' => 3,
            'negative_marks' => 2,
        ]);

        $this->assertEquals(3, $quiz->questions->count());
        $this->assertEquals(10, $quiz->questions->sum('marks'));

        //Participants
        $participant_one = Author::create([
            'name' => 'Bravo'
        ]);

        //Quiz Attempt One And Answers
        $quiz_attempt_one = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_one->id,
            'participant_type' => get_class($participant_one)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_three->id,
            ]
        );


        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_two->id,
            ]
        );
        // QuizAttemptAnswer::create(
        //     [
        //         'quiz_attempt_id' => $quiz_attempt_one->id,
        //         'quiz_question_id' => $quiz_question_two->id,
        //         'question_option_id' => $question_two_option_three->id,
        //     ]
        // );


        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_three->id,
                'question_option_id' => $question_three_option_four->id,
            ]
        );

        //Calculate Obtained marks
        $this->assertEquals(7, $quiz_attempt_one->calculate_score());
    }

    /** @test- */
    function quiz_fill_the_blank_all_correct_answers()
    {
        $computer_science = Topic::factory()->create([
            'topic' => 'Computer Science',
            'slug' => 'computer-science',
        ]);
        $algorithms = Topic::factory()->create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $data_structures = Topic::factory()->create([
            'topic' => 'Data Structures',
            'slug' => 'data-structures'
        ]);
        $computer_networks = Topic::factory()->create([
            'topic' => 'Computer Networks',
            'slug' => 'computer-networks'
        ]);
        $computer_science->children()->save($algorithms);
        $computer_science->children()->save($data_structures);
        $computer_science->children()->save($computer_networks);
        $this->assertEquals(3, $computer_science->children()->count());

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

        //Question One And Options
        $question_one = Question::factory()->create([
            'question' => 'Full Form Of CPU',
            'question_type_id' => 1,
            'is_active' => true,
        ]);
        $question_one_option_one = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'central processing unit',
            'is_correct' => true,
        ]);
        $question_one->topics()->attach([$computer_science->id, $algorithms->id]);
        $this->assertEquals(2, $question_one->topics->count());
        $this->assertEquals(1, $question_one->options->count());

        //Question Two And Options
        $question_two = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);

        $question_two_option_one = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_two_option_two = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'if',
            'is_correct' => false,
        ]);
        $question_two_option_three = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'for loop',
            'is_correct' => false,
        ]);
        $question_two_option_four = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $question_two->topics()->attach([$computer_science->id, $data_structures->id]);
        $this->assertEquals(2, $question_two->topics->count());
        $this->assertEquals(4, $question_two->options->count());

        //Question Three And Options
        $question_three = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_three_option_one = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_three_option_four = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $question_three->topics()->attach([$computer_science->id, $computer_networks->id]);
        $this->assertEquals(2, $question_three->topics->count());
        $this->assertEquals(4, $question_three->options->count());

        $this->assertEquals(3, $computer_science->questions()->count());

        //Quiz
        $quiz = Quiz::factory()->create([
            'title' => 'Computer Sceince Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0,
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'time_between_attempts' => 0,
        ]);

        //Add Question to Quiz
        $quiz_question_one =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
            'marks' => 3,
            'order' => 1,
        ]);
        $quiz_question_two =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
            'marks' => 3,
            'order' => 2,
        ]);
        $quiz_question_three =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_three->id,
            'marks' => 4,
            'order' => 2,
            'negative_marks' => 2,
        ]);

        $this->assertEquals(3, $quiz->questions->count());
        $this->assertEquals(10, $quiz->questions->sum('marks'));

        //Participants
        $participant_one = Author::create([
            'name' => 'Bravo'
        ]);
        $participant_two = Author::create([
            'name' => 'Charlie'
        ]);

        //Quiz Attempt One And Answers
        $quiz_attempt_one = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_one->id,
            'participant_type' => get_class($participant_one)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_one->id,
                'answer' => 'central processing unit'
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_three->id,
                'question_option_id' => $question_three_option_four->id,
            ]
        );

        $this->assertEquals(3, $quiz_attempt_one->answers->count());
        //Calculate Obtained marks
        $this->assertEquals(10, $quiz_attempt_one->calculate_score());
    }

    /** @test- */
    function quiz_fill_the_blank_few_wrong_answers()
    {
        $computer_science = Topic::factory()->create([
            'topic' => 'Computer Science',
            'slug' => 'computer-science',
        ]);
        $algorithms = Topic::factory()->create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $data_structures = Topic::factory()->create([
            'topic' => 'Data Structures',
            'slug' => 'data-structures'
        ]);
        $computer_networks = Topic::factory()->create([
            'topic' => 'Computer Networks',
            'slug' => 'computer-networks'
        ]);
        $computer_science->children()->save($algorithms);
        $computer_science->children()->save($data_structures);
        $computer_science->children()->save($computer_networks);
        $this->assertEquals(3, $computer_science->children()->count());

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

        //Question One And Options
        $question_one = Question::factory()->create([
            'question' => 'Full Form Of CPU',
            'question_type_id' => 3,
            'is_active' => true,
        ]);
        $question_one_option_one = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'central processing unit',
            'is_correct' => true,
        ]);
        $question_one->topics()->attach([$computer_science->id, $algorithms->id]);
        $this->assertEquals(2, $question_one->topics->count());
        $this->assertEquals(1, $question_one->options->count());

        //Question Two And Options
        $question_two = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 1,
            'is_active' => true,
        ]);

        $question_two_option_one = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_two_option_two = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'if',
            'is_correct' => false,
        ]);
        $question_two_option_three = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'for loop',
            'is_correct' => false,
        ]);
        $question_two_option_four = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $question_two->topics()->attach([$computer_science->id, $data_structures->id]);
        $this->assertEquals(2, $question_two->topics->count());
        $this->assertEquals(4, $question_two->options->count());

        //Question Three And Options
        $question_three = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_three_option_one = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_three_option_four = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $question_three->topics()->attach([$computer_science->id, $computer_networks->id]);
        $this->assertEquals(2, $question_three->topics->count());
        $this->assertEquals(4, $question_three->options->count());

        $this->assertEquals(3, $computer_science->questions()->count());

        //Quiz
        $quiz = Quiz::factory()->create([
            'title' => 'Computer Sceince Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0,
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'time_between_attempts' => 0,
        ]);

        //Add Question to Quiz
        $quiz_question_one =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
            'marks' => 3,
            'order' => 1,
        ]);
        $quiz_question_two =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
            'marks' => 3,
            'order' => 2,
        ]);
        $quiz_question_three =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_three->id,
            'marks' => 4,
            'order' => 2,
            'negative_marks' => 2,
        ]);

        $this->assertEquals(3, $quiz->questions->count());
        $this->assertEquals(10, $quiz->questions->sum('marks'));

        //Participants
        $participant_one = Author::create([
            'name' => 'Bravo'
        ]);
        $participant_two = Author::create([
            'name' => 'Charlie'
        ]);

        //Quiz Attempt One And Answers
        $quiz_attempt_one = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_one->id,
            'participant_type' => get_class($participant_one)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_one->id,
                'answer' => 'central power unit'
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_three->id,
                'question_option_id' => $question_three_option_four->id,
            ]
        );

        $this->assertEquals(3, $quiz_attempt_one->answers->count());
        //Calculate Obtained marks
        $this->assertEquals(7, $quiz_attempt_one->calculate_score());
    }

    /** @test */
    function quiz_multi_user_attempts_multi_question_types_few_wrong_answers()
    {
        $computer_science = Topic::factory()->create([
            'topic' => 'Computer Science',
            'slug' => 'computer-science',
        ]);
        $algorithms = Topic::factory()->create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $data_structures = Topic::factory()->create([
            'topic' => 'Data Structures',
            'slug' => 'data-structures'
        ]);
        $computer_networks = Topic::factory()->create([
            'topic' => 'Computer Networks',
            'slug' => 'computer-networks'
        ]);
        $computer_science->children()->save($algorithms);
        $computer_science->children()->save($data_structures);
        $computer_science->children()->save($computer_networks);

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

        //Question One And Options
        $question_one = Question::factory()->create([
            'question' => 'Full Form Of CPU',
            'question_type_id' => 3,
            'is_active' => true,
        ]);
        $question_one_option_one = QuestionOption::factory()->create([
            'question_id' => $question_one->id,
            'option' => 'central processing unit',
            'is_correct' => true,
        ]);
        $question_one->topics()->attach([$computer_science->id, $algorithms->id]);

        //Question Two And Options
        $question_two = Question::factory()->create([
            'question' => 'Which of the below is a data structure?',
            'question_type_id' => 2,
            'is_active' => true,
        ]);

        $question_two_option_one = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'array',
            'is_correct' => true,
        ]);
        $question_two_option_two = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'object',
            'is_correct' => true,
        ]);
        $question_two_option_three = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'for loop',
            'is_correct' => false,
        ]);
        $question_two_option_four = QuestionOption::factory()->create([
            'question_id' => $question_two->id,
            'option' => 'method',
            'is_correct' => false,
        ]);
        $question_two->topics()->attach([$computer_science->id, $data_structures->id]);

        //Question Three And Options
        $question_three = Question::factory()->create([
            'question' => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active' => false,
        ]);

        $question_three_option_one = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '5',
            'is_correct' => false,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => false,
        ]);
        $question_three_option_four = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '7',
            'is_correct' => true,
        ]);
        $question_three->topics()->attach([$computer_science->id, $computer_networks->id]);

        //Quiz
        $quiz = Quiz::factory()->create([
            'title' => 'Computer Sceince Quiz',
            'description' => 'Test your knowledge of computer science',
            'slug' => 'computer-science-quiz',
            'time_between_attempts' => 0,
            'total_marks' => 10,
            'pass_marks' => 6,
            'max_attempts' => 1,
            'is_published' => 1,
            'valid_from' => now(),
            'valid_upto' => now()->addDay(5),
            'time_between_attempts' => 0,
        ]);

        //Add Question to Quiz
        $quiz_question_one =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_one->id,
            'marks' => 5,
            'order' => 1,
            'negative_marks' => 1
        ]);
        $quiz_question_two =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_two->id,
            'marks' => 5,
            'order' => 2,
            'negative_marks' => 1
        ]);
        $quiz_question_three =  QuizQuestion::factory()->create([
            'quiz_id' => $quiz->id,
            'question_id' => $question_three->id,
            'marks' => 5,
            'order' => 3,
            'negative_marks' => 1,
        ]);

        $this->assertEquals(3, $quiz->questions->count());
        $this->assertEquals(15, $quiz->questions->sum('marks'));

        //Participants
        $participant_one = Author::create([
            'name' => 'Bravo'
        ]);
        $participant_two = Author::create([
            'name' => 'Charlie'
        ]);

        //Quiz Attempt One And Answers
        $quiz_attempt_one = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_one->id,
            'participant_type' => get_class($participant_one)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_one->id,
                'answer' => 'central processing unit'
            ]
        );

        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_two->id,
            ]
        );

        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_one->id,
                'quiz_question_id' => $quiz_question_three->id,
                'question_option_id' => $question_three_option_four->id,
            ]
        );

        //Quiz Attempt Two And Answers
        $quiz_attempt_two = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'participant_id' => $participant_two->id,
            'participant_type' => get_class($participant_two)
        ]);
        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_two->id,
                'quiz_question_id' => $quiz_question_one->id,
                'question_option_id' => $question_one_option_one->id,
                'answer' => 'central processing unit'
            ]
        );

        QuizAttemptAnswer::create(
            [
                'quiz_attempt_id' => $quiz_attempt_two->id,
                'quiz_question_id' => $quiz_question_two->id,
                'question_option_id' => $question_two_option_one->id,
            ]
        );

        $this->assertEquals(4, $quiz_attempt_one->answers->count());
        //Calculate Obtained marks
        $this->assertEquals(15, $quiz_attempt_one->calculate_score());

        $this->assertEquals(2, $quiz_attempt_two->answers->count());
        $this->assertEquals(3, $quiz_attempt_two->calculate_score());
    }
}
