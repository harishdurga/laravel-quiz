<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel-quiz.table_names');
        Schema::create($tableNames['quiz_questions'], function (Blueprint $table) {
            $table->id();
            $table->text('question')->nullable();
            $table->float('points')->default(0);
            $table->string('type', 100)->nullable();
            $table->unsignedBigInteger('quiz_id');
            $table->timestamps();
        });
        Schema::table($tableNames['quiz_questions'], function (Blueprint $table) use ($tableNames) {
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
        Schema::dropIfExists($tableNames['quiz_questions']);
    }
}
