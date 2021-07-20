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
        Schema::drop($this->tableNames['topics']);
        Schema::drop($this->tableNames['question_types']);
    }
}
