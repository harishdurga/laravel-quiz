<?php

namespace Harishdurga\LaravelQuiz\Models;

use Harishdurga\LaravelQuiz\Database\Factories\QuestionTypeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionType extends Model
{
    use HasFactory, SoftDeletes;

    public const MULTIPLE_CHOICE_SINGLE_ANSWER = 1,MULTIPLE_CHOICE_MULTI_ANSWER=2,FILL_IN_BLANK=3;

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
        return config('laravel-quiz.table_names.question_types');
    }

    /**
     * Backward compatibility of the attribute
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function question_type(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['name'],
            set: fn ($value) => ['name' => $value],
        );
    }

    public function questions()
    {
        return $this->hasMany(config('laravel-quiz.models.question'));
    }

    protected static function newFactory()
    {
        return QuestionTypeFactory::new();
    }
}
