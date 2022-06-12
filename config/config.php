<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Table Names on Database
    |--------------------------------------------------------------------------
    |
    | Enter the names of the tables.
    |
    */

    'table_names' => [
        'topics' => 'topics',
        'question_types' => 'question_types',
        'questions' => 'questions',
        'topicables' => 'topicables',
        'question_options' => 'question_options',
        'quizzes' => 'quizzes',
        'quiz_questions' => 'quiz_questions',
        'quiz_attempts' => 'quiz_attempts',
        'quiz_attempt_answers' => 'quiz_attempt_answers',
        'quiz_authors' => 'quiz_authors'
    ],

    /*
    |--------------------------------------------------------------------------
    | Question type mapping
    |--------------------------------------------------------------------------
    |
    | You can choose which method to use for scoring.
    |
    */

    'get_score_for_question_type' => [
        1 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_1_question',
        2 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_2_question',
        3 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_3_question',
    ]

];
