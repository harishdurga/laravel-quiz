<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAuthor extends Model
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
        return config('laravel-quiz.table_names.quiz_authors');
    }

    /**
     * @return \Harishdurga\LaravelQuiz\Models\Quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function author()
    {
        return $this->morphTo(__FUNCTION__, 'author_type', 'author_id');
    }
}
