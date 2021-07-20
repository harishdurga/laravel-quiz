<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;
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
        return \Harishdurga\LaravelQuiz\Database\Factories\QuestionOptionFactory::new();
    }
}
