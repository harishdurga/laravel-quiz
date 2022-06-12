<?php

namespace Harishdurga\LaravelQuiz\Tests\Models;

use Harishdurga\LaravelQuiz\Traits\CanAuthorQuiz;
use Illuminate\Database\Eloquent\Model;


class Editor extends Model
{
    use CanAuthorQuiz;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'editors';
}
