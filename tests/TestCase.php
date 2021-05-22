<?php

namespace Harishdurga\LaravelQuiz\Tests;

use Harishdurga\LaravelQuiz\LaravelQuizServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelQuizServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
        // import the CreatePostsTable class from the migration
        include_once __DIR__ . '/../database/migrations/2021_05_22_053359_create_quizzes_table.php';
        // run the up() method of that migration class
        // (new \CreateQuizzesTable)->up();
    }
}
