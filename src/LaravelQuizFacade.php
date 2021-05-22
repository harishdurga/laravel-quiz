<?php

namespace Harishdurga\LaravelQuiz;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Harishdurga\LaravelQuiz\Skeleton\SkeletonClass
 */
class LaravelQuizFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-quiz';
    }
}
