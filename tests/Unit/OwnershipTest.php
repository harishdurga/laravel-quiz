<?php
namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Ownership;
use Harishdurga\LaravelQuiz\Models\Question;
use Harishdurga\LaravelQuiz\Models\QuestionType;
use Harishdurga\LaravelQuiz\Tests\Models\Author;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OwnershipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function get_ownerships()
    {
        /** @var  $author Author */
        $author = Author::create(
            ['name' => "John Doe"]
        );
        $questionTypes = QuestionType::insert(
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
        $questionOne = Question::factory()->create([
            'name'             => 'How many layers in OSI model?',
            'question_type_id' => 1,
            'is_active'        => false,
        ]);
        $questionTwo = Question::factory()->create([
            'name'             => 'How many states in India?',
            'question_type_id' => 1,
            'is_active'        => false,
        ]);
        Ownership::insert([
            [
                'owner_id'=>$author->id,
                'owner_type'=>get_class($author),
                'resource_id'=>$questionOne->id,
                'resource_type' => get_class($questionOne)
            ],
            [
                'owner_id'=>$author->id,
                'owner_type'=>get_class($author),
                'resource_id'=>$questionTwo->id,
                'resource_type' => get_class($questionTwo)
            ]
        ]);
        $this->assertCount(2,$author->ownerships);
        $this->assertEquals('John Doe',$author->ownerships()->first()->owner->name);
    }
}