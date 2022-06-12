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
        Schema::create($this->tableNames['quiz_authors'], function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->nullable()->constrained($this->tableNames['quizzes'])->cascadeOnDelete();
            $table->unsignedInteger('author_id');
            $table->string('author_type');
            $table->string('author_role')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists($this->tableNames['quiz_authors']);
    }
};
