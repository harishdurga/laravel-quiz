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
        $questionTypes = [
            [
                'name' => 'multiple_choice_single_answer',
            ],
            [
                'name' => 'multiple_choice_multiple_answer',
            ],
            [
                'name' => 'fill_the_blank',
            ]
        ];
        foreach ($questionTypes as $questionType) {
            QuestionType::create($questionType);
        }
    }
}
