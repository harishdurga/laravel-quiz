<?php

/*
 * You can place your custom package configuration in here.
 */
return [
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
    ],
    'get_score_for_question_type' => [
        1 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_1_question',
        2 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_2_question',
        3 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_3_question',
    ]

];
