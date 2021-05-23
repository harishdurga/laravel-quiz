<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizTopic extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuizTopicFactory::new();
    }

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_topics', parent::getTable());
    }

    public function children()
    {
        return $this->hasMany(QuizTopic::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(QuizTopic::class, 'parent_id', 'id');
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, config('laravel-quiz.table_names.quiz_topic_quiz'), 'quiz_id', 'quiz_topic_id');
    }
}
