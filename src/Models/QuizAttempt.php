<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Quiz $quiz
 */
class QuizAttempt extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_attempts');
    }

    /**
     * @return \Harishdurga\LaravelQuiz\Models\Quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function participant()
    {
        return $this->morphTo(__FUNCTION__, 'participant_type', 'participant_id');
    }

    public function answers()
    {
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    public function caclculate_score($data = null): float
    {
        $score = 0;
        $quiz_questions_collection = $this->quiz->questions()->with('question')->orderBy('id', 'ASC')->get();
        $quiz_attempt_answers = [];
        foreach ($this->answers as $key => $quiz_attempt_answer) {
            $quiz_attempt_answers[$quiz_attempt_answer->quiz_question_id][] = $quiz_attempt_answer;
        }
        foreach ($quiz_questions_collection as $quiz_question) {
            $question = $quiz_question->question;
            $score += call_user_func_array(config('laravel-quiz.get_score_for_question_type')[$question->question_type_id], [$this, $quiz_question, $quiz_attempt_answers[$quiz_question->id], $data]);
        }
        return $score;
    }

    /**
     * @param QuizAttemptAnswer[] $quizQuestionAnswers All the answers of the quiz question
     */
    public static function get_score_for_type_1_question(QuizAttempt $quizAttempt, QuizQuestion $quizQuestion, array $quizQuestionAnswers, $data = null): float
    {
        $quiz = $quizAttempt->quiz;
        $question = $quizQuestion->question;
        $correct_answer = ($question->correct_options())->first()->id;
        $negative_marks = self::get_negative_marks_for_question($quiz, $quizQuestion);
        if (!empty($correct_answer)) {
            if (count($quizQuestionAnswers)) {
                return $quizQuestionAnswers[0]->option_id == $correct_answer ? $quizQuestion->marks : - ($negative_marks); // Return marks in case of correct answer else negative marks
            } else {
                return $quizQuestion->is_optional ? 0 : -$negative_marks; // If the question is optional, then the negative marks will be 0
            }
        } else {
            return count($quizQuestionAnswers) ? floatval($quizQuestion->marks) : 0; // Incase of no correct answer, if there is any answer then give full marks
        }
    }

    /**
     * @param QuizAttemptAnswer[] $quizQuestionAnswers All the answers of the quiz question
     */
    public static function get_score_for_type_2_question(QuizAttempt $quizAttempt, QuizQuestion $quizQuestion, array $quizQuestionAnswers, $data = null): float
    {
        $quiz = $quizAttempt->quiz;
        $question = $quizQuestion->question;
        $correct_answer = ($question->correct_options())->pluck('id');
        $negative_marks = self::get_negative_marks_for_question($quiz, $quizQuestion);
        if (!empty($correct_answer)) {
            if (count($quizQuestionAnswers)) {
                $temp_arr = [];
                foreach ($quizQuestionAnswers as  $answer) {
                    $temp_arr[] = $answer->option_id;
                }
                return $correct_answer->toArray() == $temp_arr ? $quizQuestion->marks : - ($negative_marks); // Return marks in case of correct answer else negative marks
            } else {
                return $quizQuestion->is_optional ? 0 : -$negative_marks; // If the question is optional, then the negative marks will be 0
            }
        } else {
            return count($quizQuestionAnswers) ? floatval($quizQuestion->marks) : 0; // Incase of no correct answer, if there is any answer then give full marks
        }
    }

    /**
     * @param QuizAttemptAnswer[] $quizQuestionAnswers All the answers of the quiz question
     */
    public static function get_score_for_type_3_question(QuizAttempt $quizAttempt, QuizQuestion $quizQuestion, array $quizQuestionAnswers, $data = null): float
    {
        $quiz = $quizAttempt->quiz;
        $question = $quizQuestion->question;
        $correct_answer = ($question->correct_options())->pluck('id');
        $negative_marks = self::get_negative_marks_for_question($quiz, $quizQuestion);
        if (!empty($correct_answer)) {
            if (count($quizQuestionAnswers)) {
                return  $quizQuestionAnswers[0]->answer == $correct_answer ? $quizQuestion->marks : - ($negative_marks); // Return marks in case of correct answer else negative marks
            } else {
                return $quizQuestion->is_optional ? 0 : -$negative_marks; // If the question is optional, then the negative marks will be 0
            }
        } else {
            return count($quizQuestionAnswers) ? floatval($quizQuestion->marks) : 0; // Incase of no correct answer, if there is any answer then give full marks
        }
    }

    public static function get_negative_marks_for_question(Quiz $quiz, QuizQuestion $quizQuestion): float
    {
        $negative_marking_settings = $quiz->negative_marking_settings;
        if (!$negative_marking_settings['enable_negative_marks']) { // If negative marking is disabled
            return 0;
        }
        if (!empty($quizQuestion->negative_marks)) {
            return $negative_marking_settings['negative_marking_type'] == 'fixed' ? $quizQuestion->negative_marks : ($quizQuestion->marks * ($quizQuestion->negative_marks / 100));
        } else {
            return $negative_marking_settings['negative_marking_type'] == 'fixed' ? $negative_marking_settings['negative_mark_value'] : ($quizQuestion->marks * ($negative_marking_settings['negative_mark_value'] / 100));
        }
    }
}
