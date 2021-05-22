<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel-quiz.table_names');
        Schema::create($tableNames['quiz_question_options'], function (Blueprint $table) {
            $table->id();
            $table->text('option')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->unsignedBigInteger('quiz_question_id');
            $table->timestamps();
        });
        Schema::table($tableNames['quiz_question_options'], function (Blueprint $table) use ($tableNames) {
            $table->foreign('quiz_question_id')->references('id')->on($tableNames['quiz_questions']);
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
        Schema::dropIfExists($tableNames['quiz_question_options']);
    }
}
