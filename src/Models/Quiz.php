<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function getTable()
    {
        return config('laravel-quiz.table_names.quizzes');
    }

    public function topics()
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }

    public static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuizFactory::new();
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }
}
