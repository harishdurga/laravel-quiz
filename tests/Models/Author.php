<?php

namespace Harishdurga\LaravelQuiz\Tests\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = ['id'];
    protected $table = 'authors';
}
