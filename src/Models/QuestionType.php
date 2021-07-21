<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionType extends Model
{
    use HasFactory, SoftDeletes;
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
