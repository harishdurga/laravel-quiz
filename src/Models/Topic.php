<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\TopicFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getTable()
    {
        return config('laravel-quiz.table_names.topics', parent::getTable());
    }

    /**
     * Backward compatibility of the attribute
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function topic(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['name'],
            set: fn ($value) => ['name' => $value],
        );
    }

    public function children()
    {
        return $this->hasMany(config('laravel-quiz.models.topic'), 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(config('laravel-quiz.models.topic'), 'parent_id', 'id');
    }

    public function questions()
    {
        return $this->morphedByMany(config('laravel-quiz.models.question'), 'topicable');
    }

    public function quizzes()
    {
        return $this->morphedByMany(config('laravel-quiz.models.quiz'), 'topicable');
    }

    protected static function newFactory()
    {
        return TopicFactory::new();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
