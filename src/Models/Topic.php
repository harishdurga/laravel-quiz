<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\TopicFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return TopicFactory::new();
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
