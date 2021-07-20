<?php

namespace Harishdurga\LaravelQuiz\Tests\Models;

use Harishdurga\LaravelQuiz\Traits\QuizParticipant;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use QuizParticipant;
    protected $guarded = ['id'];
    protected $table = 'authors';
}
