<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title'); //title for the quiz
            $table->text('description')->nullable(); //description about the quiz
            $table->string('code')->unique(); //unique alpha numeric code to identify the quiz
            $table->float('points_to_pass')->default(0); //number of points needed to pass this quiz
            $table->json('additional_data')->nullable(); //Any other additional data about the quiz in key value format
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
        Schema::dropIfExists('quizzes');
    }
}
