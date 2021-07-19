<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuizFactory::new();
    }

    public function getTable()
    {
        return config('laravel-quiz.table_names.quizzes', parent::getTable());
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }

    public function quiz_topics()
    {
        return $this->belongsToMany(QuizTopic::class, config('laravel-quiz.table_names.quiz_topic_quiz'), 'quiz_topic_id', 'quiz_id');
    }

    /**
     * Get the parent model that this quiz belongs to.
     */
    public function author()
    {
        return $this->morphTo(__FUNCTION__, 'author_type', 'author_id');
    }
}
