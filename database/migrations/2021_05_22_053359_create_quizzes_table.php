<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel-quiz.table_names');
        Schema::create($tableNames['quizzes'], function (Blueprint $table) {
            $table->id();
            $table->string('title'); //title for the quiz
            $table->text('description')->nullable(); //description about the quiz
            $table->string('code')->unique(); //unique alpha numeric code to identify the quiz
            $table->float('points_to_pass')->default(0); //number of points needed to pass this quiz
            $table->json('additional_data')->nullable(); //Any other additional data about the quiz in key value format
            $table->unsignedBigInteger('author_id')->nullable(); //Author model id
            $table->string('author_type')->nullable(); //Author model type
            $table->tinyInteger('is_published')->default(0); //Quiz is published if 1, unblished 0
            $table->unsignedInteger('no_of_attempts')->default(0); //Number of times the quiz can be attempted. 0 = unlimited attempts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('laravel-quiz.table_names');
        Schema::dropIfExists($tableNames['quizzes']);
    }
}
