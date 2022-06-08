<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\QuestionFactory;
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

    public function getTable()
    {
        return config('laravel-quiz.table_names.questions');
    }

    protected static function newFactory()
    {
        return QuestionFactory::new();
    }

    public function question_type()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function topics()
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function quiz_questions()
    {
        return $this->hasMany(QuizQuestion::class);
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
