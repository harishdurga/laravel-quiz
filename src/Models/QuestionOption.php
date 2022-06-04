<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\QuestionOptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOption extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('laravel-quiz.table_names.question_options');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    protected static function newFactory()
    {
        return QuestionOptionFactory::new();
    }

    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }
}
