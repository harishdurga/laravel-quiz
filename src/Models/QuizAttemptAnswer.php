<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAttemptAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_attempt_answers');
    }
    public function quiz_attempt()
    {
        return $this->belongsTo(QuizAttempt::class);
    }
    public function quiz_question()
    {
        return $this->belongsTo(QuizQuestion::class);
    }
    public function question_option()
    {
        return $this->belongsTo(QuestionOption::class);
    }
}
