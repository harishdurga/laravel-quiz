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
}
