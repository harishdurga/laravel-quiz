<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\QuestionTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionType extends Model
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
        return config('laravel-quiz.table_names.question_types');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    protected static function newFactory()
    {
        return QuestionTypeFactory::new();
    }
}
