<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesTable extends Migration
{
    public array $tableNames;
    public function __construct()
    {
        $this->tableNames = config('laravel-quiz.table_names');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Topics Table
        Schema::create($this->tableNames['topics'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('topic');
            $table->string('slug')->unique();
            $table->unsignedInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('parent_id')->references('id')->on($this->tableNames['topics'])->onDelete('SET NULL');
        });

        //Question Types Table
        Schema::create($this->tableNames['question_types'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_type');
            $table->timestamps();
            $table->softDeletes();
        });

        //Questions Table
        Schema::create($this->tableNames['questions'], function (Blueprint $table) {
            $table->increments('id');
            $table->text('question');
            $table->unsignedInteger('question_type_id');
            $table->text('media_url')->nullable();
            $table->string('media_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('question_type_id')->references('id')->on($this->tableNames['question_types'])->onDelete('cascade');
        });

        //Quiz, Questions and Topics Relations Table
        Schema::create($this->tableNames['topicables'], function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('topic_id');
            $table->unsignedInteger('topicable_id');
            $table->string('topicable_type');
            $table->timestamps();
            $table->foreign('topic_id')->references('id')->on($this->tableNames['topics'])->onDelete('cascade');
        });

        //Question Options Table
        Schema::create($this->tableNames['question_options'], function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->string('option')->nullable();
            $table->string('media_url')->nullable();
            $table->string('media_type')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('question_id')->references('id')->on($this->tableNames['questions'])->onDelete('cascade');
        });

        //Quizzes Table
        Schema::create($this->tableNames['quizzes'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->float('total_marks')->default(0); //0 means no marks
            $table->float('pass_marks')->default(0); //0 means no pass marks
            $table->unsignedInteger('max_attempts')->default(0); //0 means unlimited attempts
            $table->tinyInteger('is_published')->default(0); //0 means not published, 1 means published
            $table->string('media_url')->nullable(); //Can be used for cover image, logo etc.
            $table->string('media_type')->nullable(); //image,video,audio etc.
            $table->unsignedInteger('duration')->default(0); //0 means no duration
            $table->timestamp('valid_from')->default(now());
            $table->timestamp('valid_upto')->nullable(); //null means no expiry
            $table->unsignedInteger('time_between_attempts')->default(0); //0 means no time between attempts, immediately
            $table->timestamps();
            $table->softDeletes();
        });

        //Quiz Questions Table
        Schema::create($this->tableNames['quiz_questions'], function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quiz_id');
            $table->unsignedInteger('question_id');
            $table->unsignedFloat('marks')->default(0); //0 means no marks
            $table->unsignedFloat('negative_marks')->default(0); //0 means no negative marks in case of wrong answer
            $table->boolean('is_optional')->default(false); //0 means not optional, 1 means optional
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('quiz_id')->references('id')->on($this->tableNames['quizzes'])->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on($this->tableNames['questions'])->onDelete('cascade');
            $table->unique(['quiz_id', 'question_id']);
        });

        //Quiz Attempts Table
        Schema::create($this->tableNames['quiz_attempts'], function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quiz_id');
            $table->unsignedInteger('participant_id');
            $table->string('participant_type');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('quiz_id')->references('id')->on($this->tableNames['quizzes'])->onDelete('cascade');
        });

        //Quiz Attempt Answers Table
        Schema::create($this->tableNames['quiz_attempt_answers'], function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quiz_attempt_id');
            $table->unsignedInteger('quiz_question_id');
            $table->unsignedInteger('question_option_id');
            $table->string('answer')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('quiz_attempt_id')->references('id')->on($this->tableNames['quiz_attempts'])->onDelete('cascade');
            $table->foreign('quiz_question_id')->references('id')->on($this->tableNames['quiz_questions'])->onDelete('cascade');
            $table->foreign('question_option_id')->references('id')->on($this->tableNames['question_options'])->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableNames['topics'], function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        Schema::table($this->tableNames['topicables'], function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
        });
        Schema::table($this->tableNames['question_options'], function (Blueprint $table) {
            $table->dropForeign(['question_id']);
        });
        Schema::table($this->tableNames['quiz_questions'], function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropForeign(['question_id']);
        });
        Schema::table($this->tableNames['quiz_attempts'], function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
        });
        Schema::table($this->tableNames['quiz_attempt_answers'], function (Blueprint $table) {
            $table->dropForeign(['quiz_attempt_id']);
            $table->dropForeign(['quiz_question_id']);
            $table->dropForeign(['question_option_id']);
        });
        Schema::drop($this->tableNames['quiz_attempt_answers']);
        Schema::drop($this->tableNames['quiz_attempts']);
        Schema::drop($this->tableNames['quiz_questions']);
        Schema::drop($this->tableNames['topicables']);
        Schema::drop($this->tableNames['question_options']);
        Schema::drop($this->tableNames['questions']);
        Schema::drop($this->tableNames['topics']);
        Schema::drop($this->tableNames['question_types']);
        Schema::drop($this->tableNames['quizzes']);
    }
}
