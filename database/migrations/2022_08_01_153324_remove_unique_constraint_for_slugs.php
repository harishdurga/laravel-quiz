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
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes($this->tableNames['topics']);
            if (array_key_exists($this->tableNames['topics'] . "_slug_unique", $indexesFound))
                $table->dropUnique($this->tableNames['topics'] . "_slug_unique");
        });

        Schema::table($this->tableNames['quizzes'], function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes($this->tableNames['quizzes']);
            if (array_key_exists($this->tableNames['quizzes'] . "_slug_unique", $indexesFound))
                $table->dropUnique($this->tableNames['quizzes'] . "_slug_unique");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
