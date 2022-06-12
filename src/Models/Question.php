<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
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
        return config('laravel-quiz.table_names.questions');
    }

    /**
     * Backward compatibility of the attribute
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function question(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['name'],
            set: fn ($value) => ['name' => $value],
        );
    }

    public function question_type()
    {
        return $this->belongsTo(config('laravel-quiz.models.question_type'));
    }

    public function topics()
    {
        return $this->morphToMany(config('laravel-quiz.models.topic'), 'topicable');
    }

    public function options()
    {
        return $this->hasMany(config('laravel-quiz.models.question_option'));
    }

    public function quiz_questions()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_question'));
    }

    protected static function newFactory()
    {
        return QuestionFactory::new();
    }

    public function correct_options(): Collection
    {
        return $this->options()->where('is_correct', 1)->get();
    }

    /**
     * Scope a query to only include question with options.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasOptions($query)
    {
        return $query->has('options', '>', 0);
    }
}
