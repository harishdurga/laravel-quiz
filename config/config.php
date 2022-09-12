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
        'topics'               => 'topics',
        'question_types'       => 'question_types',
        'questions'            => 'questions',
        'topicables'           => 'topicables',
        'question_options'     => 'question_options',
        'quizzes'              => 'quizzes',
        'quiz_questions'       => 'quiz_questions',
        'quiz_attempts'        => 'quiz_attempts',
        'quiz_attempt_answers' => 'quiz_attempt_answers',
        'quiz_authors'         => 'quiz_authors'
    ],

    /*
    |--------------------------------------------------------------------------
    | Models Name
    |--------------------------------------------------------------------------
    |
    | Allow to override Quiz table to extend code
    |
    */

    'models' => [

        /*
         * Default Harishdurga\LaravelQuiz\Models\Question::class
         */

        'question' => Harishdurga\LaravelQuiz\Models\Question::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\Question::class
         */

        'question_option' => Harishdurga\LaravelQuiz\Models\QuestionOption::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\Question::class
         */

        'question_type' => Harishdurga\LaravelQuiz\Models\QuestionType::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\Quiz::class
         */

        'quiz' => Harishdurga\LaravelQuiz\Models\Quiz::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\QuizAttempt::class
         */

        'quiz_attempt' => Harishdurga\LaravelQuiz\Models\QuizAttempt::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\QuizAttempt::class
         */

        'quiz_attempt_answer' => Harishdurga\LaravelQuiz\Models\QuizAttemptAnswer::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\QuizAttempt::class
         */

        'quiz_author' => Harishdurga\LaravelQuiz\Models\QuizAuthor::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\QuizAttempt::class
         */

        'quiz_question' => Harishdurga\LaravelQuiz\Models\QuizQuestion::class,

        /*
         * Default Harishdurga\LaravelQuiz\Models\QuizAttempt::class
         */

        'topic' => Harishdurga\LaravelQuiz\Models\Topic::class,
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
    ],

    /*
    |--------------------------------------------------------------------------
    | Question type answer/solution render
    |--------------------------------------------------------------------------
    |
    | Render correct answer and given response for different question types
    |
    */
    'render_answers_responses'    => [
        1  => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::renderQuestionType1Answers',
        2  => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::renderQuestionType2Answers',
        3  => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::renderQuestionType3Answers',
    ]

];
