<?php

namespace Harishdurga\LaravelQuiz\Tests\Unit;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Harishdurga\LaravelQuiz\Tests\TestCase;
use Harishdurga\LaravelQuiz\Models\QuizAuthor;
use Harishdurga\LaravelQuiz\Tests\Models\Author;
use Harishdurga\LaravelQuiz\Tests\Models\Editor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class QuizAuthorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    function quiz_authors()
    {
        $admin = Author::create(
            ['name' => "John Doe"]
        );
        $editor_one = Editor::create(
            ['name' => "Jane Doe"]
        );
        $editor_two = Editor::create(
            ['name' => "Mike Poe"]
        );
        $quiz = Quiz::factory()->make()->create([
            'name' => 'Sample Quiz',
            'slug' => 'sample-quiz'
        ]);
        QuizAuthor::create([
            'quiz_id' => $quiz->id,
            'author_id' => $admin->id,
            'author_type' => get_class($admin),
            'author_role' => 'admin',
        ]);
        QuizAuthor::create([
            'quiz_id' => $quiz->id,
            'author_id' => $editor_one->id,
            'author_type' => get_class($editor_one),
            'author_role' => 'editor',
        ]);
        QuizAuthor::create([
            'quiz_id' => $quiz->id,
            'author_id' => $editor_two->id,
            'author_type' => get_class($editor_two),
            'author_role' => 'editor',
        ]);
        $this->assertEquals(3, $quiz->quizAuthors->count());
        $quiAdmin = $quiz->quizAuthors()->where('author_role', 'admin')->first();
        $this->assertEquals($admin->id, $quiAdmin->author->id);
        $this->assertEquals(get_class($admin), get_class($quiAdmin->author));
        $quiEditors = $quiz->quizAuthors()->where('author_role', 'editor')->get();
        $this->assertEquals(2, $quiEditors->count());
        $this->assertEquals($editor_one->id, $quiEditors->first()->author->id);
        $this->assertEquals(get_class($editor_one), get_class($quiEditors->first()->author));
        $this->assertEquals(1, $editor_one->quizzes->count());
    }
}
