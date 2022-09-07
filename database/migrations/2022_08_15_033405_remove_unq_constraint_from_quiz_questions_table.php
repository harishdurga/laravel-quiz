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
        Schema::table($this->tableNames['quiz_questions'], function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes($this->tableNames['quiz_questions']);
            if (array_key_exists($this->tableNames['quiz_questions'] . "_quiz_id_question_id_unique", $indexesFound)) {
                $table->dropForeign($this->tableNames['quiz_questions'] . '_question_id_foreign');
                $table->dropForeign($this->tableNames['quiz_questions'] . '_quiz_id_foreign');
                $table->dropUnique($this->tableNames['quiz_questions'] . "_quiz_id_question_id_unique");
                $table->foreignId('quiz_id')->change()->constrained($this->tableNames['quizzes'])->cascadeOnDelete();
                $table->foreignId('question_id')->change()->constrained($this->tableNames['questions'])->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            //
        });
    }
};
