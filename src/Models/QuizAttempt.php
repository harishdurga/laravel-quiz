<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Quiz $quiz
 */
class QuizAttempt extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_attempts');
    }

    public function quiz()
    {
        return $this->belongsTo(config('laravel-quiz.models.quiz'));
    }

    public function participant()
    {
        return $this->morphTo(__FUNCTION__, 'participant_type', 'participant_id');
    }

    public function answers()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_attempt_answer'));
    }

    public function calculate_score($data = null): float
    {
        $score = 0;
        $quiz_questions_collection = $this->quiz->questions()->with('question')->orderBy('id', 'ASC')->get();
        $quiz_attempt_answers = [];
        foreach ($this->answers as $quiz_attempt_answer) {
            $quiz_attempt_answers[$quiz_attempt_answer->quiz_question_id][] = $quiz_attempt_answer;
        }
        foreach ($quiz_questions_collection as $quiz_question) {
            $question = $quiz_question->question;
            $score += call_user_func_array(config('laravel-quiz.get_score_for_question_type')[$question->question_type_id], [$this, $quiz_question, $quiz_attempt_answers[$quiz_question->id] ?? [], $data]);
        }
        return $score;
    }

    /**
     * @param QuizAttemptAnswer[] $quizQuestionAnswers All the answers of the quiz question
     */
    public static function get_score_for_type_1_question(QuizAttempt $quizAttempt, QuizQuestion $quizQuestion, array|Collection $quizQuestionAnswers, $data = null): float
    {
        $quiz = $quizAttempt->quiz;
        $question = $quizQuestion->question;
        $correct_answer = ($question->correct_options())->first()->id;
        $negative_marks = self::get_negative_marks_for_question($quiz, $quizQuestion);
        if (!empty($correct_answer)) {
            if (count($quizQuestionAnswers)) {
                return $quizQuestionAnswers[0]->question_option_id == $correct_answer ? $quizQuestion->marks : -($negative_marks); // Return marks in case of correct answer else negative marks
            }
            return $quizQuestion->is_optional ? 0 : -$negative_marks; // If the question is optional, then the negative marks will be 0
        }
        return count($quizQuestionAnswers) ? (float)$quizQuestion->marks : 0; // Incase of no correct answer, if there is any answer then give full marks
    }

    /**
     * @param QuizAttemptAnswer[] $quizQuestionAnswers All the answers of the quiz question
     */
    public static function get_score_for_type_2_question(QuizAttempt $quizAttempt, QuizQuestion $quizQuestion, array|Collection $quizQuestionAnswers, $data = null): float
    {
        $quiz = $quizAttempt->quiz;
        $question = $quizQuestion->question;
        $correct_answer = ($question->correct_options())->pluck('id');
        $negative_marks = self::get_negative_marks_for_question($quiz, $quizQuestion);
        if (!empty($correct_answer)) {
            if (count($quizQuestionAnswers)) {
                $temp_arr = [];
                foreach ($quizQuestionAnswers as $answer) {
                    $temp_arr[] = $answer->question_option_id;
                }
                return $correct_answer->toArray() == $temp_arr ? $quizQuestion->marks : -($negative_marks); // Return marks in case of correct answer else negative marks
            }
            return $quizQuestion->is_optional ? 0 : -$negative_marks; // If the question is optional, then the negative marks will be 0
        }
        return count($quizQuestionAnswers) ? (float)$quizQuestion->marks : 0; // Incase of no correct answer, if there is any answer then give full marks
    }

    /**
     * @param QuizAttemptAnswer[] $quizQuestionAnswers All the answers of the quiz question
     */
    public static function get_score_for_type_3_question(QuizAttempt $quizAttempt, QuizQuestion $quizQuestion, array|Collection $quizQuestionAnswers, $data = null): float
    {
        $quiz = $quizAttempt->quiz;
        $question = $quizQuestion->question;
        $correct_answer = ($question->correct_options())->first()->option;
        $negative_marks = self::get_negative_marks_for_question($quiz, $quizQuestion);
        if (!empty($correct_answer)) {
            if (count($quizQuestionAnswers)) {
                return $quizQuestionAnswers[0]->answer == $correct_answer ? $quizQuestion->marks : -($negative_marks); // Return marks in case of correct answer else negative marks
            }
            return $quizQuestion->is_optional ? 0 : -$negative_marks; // If the question is optional, then the negative marks will be 0
        }
        return count($quizQuestionAnswers) ? (float)$quizQuestion->marks : 0; // Incase of no correct answer, if there is any answer then give full marks
    }

    public static function get_negative_marks_for_question(Quiz $quiz, QuizQuestion $quizQuestion): float
    {
        $negative_marking_settings = $quiz->negative_marking_settings ?? [
            'enable_negative_marks' => true,
            'negative_marking_type' => 'fixed',
            'negative_mark_value'   => 0,
        ];
        if (!$negative_marking_settings['enable_negative_marks']) { // If negative marking is disabled
            return 0;
        }
        if (!empty($quizQuestion->negative_marks)) {
            return $negative_marking_settings['negative_marking_type'] == 'fixed' ?
                ($quizQuestion->negative_marks < 0 ? -$quizQuestion->negative_marks : $quizQuestion->negative_marks) : ($quizQuestion->marks * (($quizQuestion->negative_marks < 0 ? -$quizQuestion->negative_marks : $quizQuestion->negative_marks) / 100));
        }
        return $negative_marking_settings['negative_marking_type'] == 'fixed' ? ($negative_marking_settings['negative_mark_value'] < 0 ? -$negative_marking_settings['negative_mark_value'] : $negative_marking_settings['negative_mark_value']) : ($quizQuestion->marks * (($negative_marking_settings['negative_mark_value'] < 0 ? -$negative_marking_settings['negative_mark_value'] : $negative_marking_settings['negative_mark_value']) / 100));
    }

    private function validateQuizQuestion(QuizQuestion $quizQuestion, mixed $data = null): array
    {
        $isCorrect = true;
        $actualQuestion = $quizQuestion->question;
        $answers = $quizQuestion->answers;
        $questionType = $actualQuestion->question_type;
        $score = call_user_func_array(config('laravel-quiz.get_score_for_question_type')[$questionType->id], [$this, $quizQuestion, $answers ?? [], $data]);
        if ($score <= 0) {
            $isCorrect = false;
        }
        list($correctAnswer, $userAnswer) = config('laravel-quiz.render_answers_responses')[$questionType->id]($quizQuestion, $data);
        return [
            'score'          => $score,
            'is_correct'     => $isCorrect,
            'correct_answer' => $correctAnswer,
            'user_answer'    => $userAnswer
        ];
    }

    /**
     * @param int|null $quizQuestionId
     * @param $data mixed|null data to be passed to the user defined function to evaluate different question types
     * @return array|null [1=>['score'=>10,'is_correct'=>true,'correct_answer'=>'a','user_answer'=>'a']]
     */
    public function validate(int|null $quizQuestionId = null, mixed $data = null): array|null
    {
        if ($quizQuestionId) {
            $quizQuestion = QuizQuestion::where(['quiz_id' => $this->quiz_id, 'id' => $quizQuestionId])
                ->with('question')
                ->with('answers')
                ->with('question.options')->first();
            if ($quizQuestion) {
                return [$quizQuestionId => $this->validateQuizQuestion($quizQuestion, $data)];
            }
            return null; //QuizQuestion is empty
        }
        $quizQuestions = QuizQuestion::where(['quiz_id' => $this->quiz_id])->get();
        if ($quizQuestions->count() == 0) {
            return null;
        }
        $result = [];
        foreach ($quizQuestions as $quizQuestion) {
            $result[$quizQuestion->id] = $this->validateQuizQuestion($quizQuestion);
        }
        return $result;
    }

    public static function renderQuestionType1Answers(QuizQuestion $quizQuestion, mixed $data = null)
    {
        /**
         * @var Question $actualQuestion
         */
        $actualQuestion = $quizQuestion->question;
        $answers = $quizQuestion->answers;
        $questionOptions = $actualQuestion->options;
        $correctAnswer = $actualQuestion->correct_options()->first()?->option;
        $givenAnswer = $answers->first()?->question_option_id;
        foreach ($questionOptions as $questionOption) {
            if ($questionOption->id == $givenAnswer) {
                $givenAnswer = $questionOption->option;
                break;
            }
        }
        return [$correctAnswer, $givenAnswer];
    }

    public static function renderQuestionType2Answers(QuizQuestion $quizQuestion, mixed $data = null)
    {
        $actualQuestion = $quizQuestion->question;
        $userAnswersCollection = $quizQuestion->answers;
        $correctAnswersCollection = $actualQuestion->correct_options();
        $correctAnswers = $userAnswers = [];
        foreach ($correctAnswersCollection as $correctAnswer) {
            $correctAnswers[] = $correctAnswer->option;
        }
        foreach ($userAnswersCollection as $userAnswer) {
            $userAnswers[] = $userAnswer?->question_option?->option;
        }
        return [$correctAnswers, $userAnswers];
    }

    public static function renderQuestionType3Answers(QuizQuestion $quizQuestion, mixed $data = null)
    {
        $actualQuestion = $quizQuestion->question;
        $userAnswersCollection = $quizQuestion->answers;
        $correctAnswersCollection = $actualQuestion->correct_options();
        $userAnswer = $userAnswersCollection->first()?->answer;
        $correctAnswer = $correctAnswersCollection->first()?->option;
        return [$correctAnswer, $userAnswer];
    }
}
