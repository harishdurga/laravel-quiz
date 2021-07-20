<?php

namespace Harishdurga\LaravelQuiz\Database\Seeders;

use Harishdurga\LaravelQuiz\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuestionType::create(
            [
                [
                    'question_type' => 'multiple_choice_single_answer',
                ],
                [
                    'question_type' => 'multiple_choice_multiple_answer',
                ],
                [
                    'question_type' => 'fill_the_blank',
                ]
            ]
        );
    }
}
