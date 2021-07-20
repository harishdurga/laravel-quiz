<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel-quiz.table_names');
        Schema::create($tableNames['quiz_attempts'], function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('participant_id');
            $table->string('participant_type');
            $table->timestamps();
        });
        Schema::table($tableNames['quiz_attempts'], function (Blueprint $table) use ($tableNames) {
            $table->foreign('quiz_id')->references('id')->on($tableNames['quizzes'])->onDelete('cascade');
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
        Schema::dropIfExists($tableNames['quiz_attempts']);
    }
}
