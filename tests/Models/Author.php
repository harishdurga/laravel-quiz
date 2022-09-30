<?php

namespace Harishdurga\LaravelQuiz\Tests\Models;

use Harishdurga\LaravelQuiz\Traits\BeAOwner;
use Harishdurga\LaravelQuiz\Traits\CanAuthorQuiz;
use Harishdurga\LaravelQuiz\Traits\QuizParticipant;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use QuizParticipant, CanAuthorQuiz, BeAOwner;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'authors';
}
