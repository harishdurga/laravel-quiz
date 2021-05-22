<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuizFactory::new();
    }

    public function getTable()
    {
        return config('laravel-quiz.table_names.quizzes', parent::getTable());
    }
}
