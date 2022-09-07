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
        // Topics Table
        Schema::create($this->tableNames['topics'], function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('slug');
            $table->foreignId('parent_id')->nullable()->constrained($this->tableNames['topics'])->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Question Types Table
        Schema::create($this->tableNames['question_types'], function (Blueprint $table) {
            $table->id();
            $table->string('question_type');
            $table->timestamps();
            $table->softDeletes();
        });

        // Questions Table
        Schema::create($this->tableNames['questions'], function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->foreignId('question_type_id')->nullable()->constrained($this->tableNames['question_types'])->cascadeOnDelete();
            $table->text('media_url')->nullable();
            $table->string('media_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Quiz, Questions and Topics Relations Table
        Schema::create($this->tableNames['topicables'], function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained($this->tableNames['topics'])->cascadeOnDelete();
            $table->unsignedInteger('topicable_id');
            $table->string('topicable_type');
            $table->timestamps();
        });

        // Question Options Table
        Schema::create($this->tableNames['question_options'], function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->nullable()->constrained($this->tableNames['questions'])->cascadeOnDelete();
            $table->string('option')->nullable();
            $table->string('media_url')->nullable();
            $table->string('media_type')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Quizzes Table
        Schema::create($this->tableNames['quizzes'], function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
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

        // Quiz Questions Table
        Schema::create($this->tableNames['quiz_questions'], function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->nullable()->constrained($this->tableNames['quizzes'])->cascadeOnDelete();
            $table->foreignId('question_id')->nullable()->constrained($this->tableNames['questions'])->cascadeOnDelete();
            $table->unsignedFloat('marks')->default(0); //0 means no marks
            $table->unsignedFloat('negative_marks')->default(0); //0 means no negative marks in case of wrong answer
            $table->boolean('is_optional')->default(false); //0 means not optional, 1 means optional
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Quiz Attempts Table
        Schema::create($this->tableNames['quiz_attempts'], function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->nullable()->constrained($this->tableNames['quizzes'])->cascadeOnDelete();
            $table->unsignedInteger('participant_id');
            $table->string('participant_type');
            $table->timestamps();
            $table->softDeletes();
        });

        // Quiz Attempt Answers Table
        Schema::create($this->tableNames['quiz_attempt_answers'], function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->nullable()->constrained($this->tableNames['quiz_attempts'])->cascadeOnDelete();
            $table->foreignId('quiz_question_id')->nullable()->constrained($this->tableNames['quiz_questions'])->cascadeOnDelete();
            $table->foreignId('question_option_id')->nullable()->constrained($this->tableNames['question_options'])->cascadeOnDelete();
            $table->string('answer')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
