<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $gaurded = ['id'];
    public function getTable()
    {
        return config('laravel-quiz.table_names.questions');
    }
    protected static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuestionFactory::new();
    }

    public function question_type()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function topics()
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function quiz_questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
