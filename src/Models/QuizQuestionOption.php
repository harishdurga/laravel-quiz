<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class QuizQuestionOption extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_question_options', parent::getTable());
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    protected static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuizQuestionOptionFactory::new();
    }
}
