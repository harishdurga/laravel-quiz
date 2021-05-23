<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('laravel-quiz.table_names');
        Schema::create($tableNames['quiz_topics'], function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table($tableNames['quiz_topics'], function (Blueprint $table) use ($tableNames) {
            $table->foreign('parent_id')->references('id')->on($tableNames['quiz_topics']);
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
        Schema::dropIfExists($tableNames['quiz_topics']);
    }
}
