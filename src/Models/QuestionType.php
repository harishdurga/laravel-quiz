<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('laravel-quiz.table_names.question_types');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    protected static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuestionTypeFactory::new();
    }
}
