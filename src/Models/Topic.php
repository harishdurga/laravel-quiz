<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\TopicFactory::new();
    }

    public function getTable()
    {
        return config('laravel-quiz.table_names.topics', parent::getTable());
    }

    public function children()
    {
        return $this->hasMany(Topic::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Topic::class, 'parent_id', 'id');
    }

    public function questions()
    {
        return $this->morphedByMany(Question::class, 'topicable');
    }

    public function quizzes()
    {
        return $this->morphedByMany(Quiz::class, 'topicable');
    }
}
