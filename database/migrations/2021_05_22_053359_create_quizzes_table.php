<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on($this->tableNames['topics'])->onDelete('cascade');
        });

        //Question Types Table
        Schema::create($this->tableNames['question_types'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_type');
            $table->timestamps();
        });

        //Questions Table
        Schema::create($this->tableNames['questions'], function (Blueprint $table) {
            $table->increments('id');
            $table->text('question');
            $table->unsignedBigInteger('question_type_id');
            $table->text('media_url')->nullable();
            $table->string('media_type')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('question_type_id')->references('id')->on($this->tableNames['question_types'])->onDelete('cascade');
        });

        //Quiz, Questions and Topics Relations Table
        Schema::create($this->tableNames['topicables'], function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('topicable_id');
            $table->string('topicable_type');
            $table->timestamps();
            $table->foreign('topic_id')->references('id')->on($this->tableNames['topics'])->onDelete('cascade');
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
        Schema::table($this->tableNames['quiz_question_topics'], function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
        });
        Schema::table($this->tableNames['topicables'], function (Blueprint $table) {
            $table->dropForeign(['question_type_id']);
        });
        Schema::drop($this->tableNames['topicables']);
        Schema::drop($this->tableNames['questions']);
        Schema::drop($this->tableNames['topics']);
        Schema::drop($this->tableNames['question_types']);
    }
}
