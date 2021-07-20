<?php

namespace Harishdurga\LaravelQuiz\Tests\Feature;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Models\Topic;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Harishdurga\LaravelQuiz\Database\Factories\QuestionFactory;
use Harishdurga\LaravelQuiz\Models\QuestionOption;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function quiz()
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
            'is_correct' => true,
        ]);
        $question_three_option_two = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '8',
            'is_correct' => false,
        ]);
        $question_three_option_three = QuestionOption::factory()->create([
            'question_id' => $question_three->id,
            'option' => '10',
            'is_correct' => true,
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
    }
}
