<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_questions');
    }

    protected static function newFactory()
    {
        return new \Harishdurga\LaravelQuiz\Database\Factories\QuizQuestionFactory();
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
