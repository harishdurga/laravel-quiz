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
    'answer_pickers' => [
        1 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_correct_answer_type_one',
        2 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_correct_answer_type_two',
        3 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_correct_answer_type_three',
    ]

];
