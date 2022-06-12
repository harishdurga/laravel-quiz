<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAttemptAnswer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_attempt_answers');
    }

    public function quiz_attempt()
    {
        return $this->belongsTo(config('laravel-quiz.models.quiz_attempt'));
    }

    public function quiz_question()
    {
        return $this->belongsTo(config('laravel-quiz.models.quiz_question'));
    }

    public function question_option()
    {
        return $this->belongsTo(config('laravel-quiz.models.question_option'));
    }
}
