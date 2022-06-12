<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\QuizQuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizQuestion extends Model
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
        return config('laravel-quiz.table_names.quiz_questions');
    }

    public function quiz()
    {
        return $this->belongsTo(config('laravel-quiz.models.quiz'));
    }

    public function question()
    {
        return $this->belongsTo(config('laravel-quiz.models.question'));
    }

    public function answers()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_attempt_answer'));
    }

    protected static function newFactory()
    {
        return new QuizQuestionFactory();
    }
}
