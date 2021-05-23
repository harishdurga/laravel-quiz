<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizTopicsQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel-quiz.table_names');
        Schema::create($tableNames['quiz_topic_quiz'], function (Blueprint $table) {
            $table->unsignedBigInteger('quiz_topic_id')->index();
            $table->unsignedBigInteger('quiz_id')->index();
            $table->primary(['quiz_topic_id', 'quiz_id']);
            $table->timestamps();
        });
        Schema::table($tableNames['quiz_topic_quiz'], function (Blueprint $table) use ($tableNames) {
            $table->foreign('quiz_topic_id')->references('id')->on($tableNames['quiz_topics']);
            $table->foreign('quiz_id')->references('id')->on($tableNames['quizzes']);
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
        Schema::dropIfExists($tableNames['quiz_topic_quiz']);
    }
}
