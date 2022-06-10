<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
        Schema::table($this->tableNames['topics'], function (Blueprint $table) {
            $table->renameColumn('topic', 'name');
        });
        Schema::table($this->tableNames['question_types'], function (Blueprint $table) {
            $table->renameColumn('question_type', 'name');
        });
        Schema::table($this->tableNames['questions'], function (Blueprint $table) {
            $table->renameColumn('question', 'name');
        });
        Schema::table($this->tableNames['question_options'], function (Blueprint $table) {
            $table->renameColumn('option', 'name');
        });
        Schema::table($this->tableNames['quizzes'], function (Blueprint $table) {
            $table->renameColumn('title', 'name');
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
            $table->renameColumn('name', 'topic');
        });
        Schema::table($this->tableNames['question_types'], function (Blueprint $table) {
            $table->renameColumn('name', 'question_type');
        });
        Schema::table($this->tableNames['questions'], function (Blueprint $table) {
            $table->renameColumn('name', 'question');
        });
        Schema::table($this->tableNames['question_options'], function (Blueprint $table) {
            $table->renameColumn('name', 'option');
        });
        Schema::table($this->tableNames['quizzes'], function (Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
    }
};
