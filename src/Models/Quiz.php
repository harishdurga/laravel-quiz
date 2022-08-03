<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Harishdurga\LaravelQuiz\Database\Factories\QuizFactory;

class Quiz extends Model
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'negative_marking_settings' => 'json',
    ];

    const FIXED_NEGATIVE_TYPE = 'fixed';
    const PERCENTAGE_NEGATIVE_TYPE = 'percentage';

    public function getTable()
    {
        return config('laravel-quiz.table_names.quizzes');
    }

    /**
     * Backward compatibility of the attribute
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function title(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['name'],
            set: fn ($value) => ['name' => $value],
        );
    }

    public function topics()
    {
        return $this->morphToMany(config('laravel-quiz.models.topic'), 'topicable');
    }

    public function questions()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_question'));
    }

    public function attempts()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_attempt'));
    }

    public static function newFactory()
    {
        return QuizFactory::new();
    }

    /**
     * Interact with the user's address.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function negativeMarkingSettings(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => empty($value) ? [
                'enable_negative_marks' => true,
                'negative_marking_type' => Quiz::FIXED_NEGATIVE_TYPE,
                'negative_mark_value' => 0
            ] : json_decode($value, true),
        );
    }

    public function quizAuthors()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_author'));
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
