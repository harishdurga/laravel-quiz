<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Quiz extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    const FIXED_NEGATIVE_TYPE = 'fixed';
    const PERCENTAGE_NEGATIVE_TYPE = 'percentage';
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'negative_marking_settings' => 'json',
    ];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quizzes');
    }

    public function topics()
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }

    public static function newFactory()
    {
        return \Harishdurga\LaravelQuiz\Database\Factories\QuizFactory::new();
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
