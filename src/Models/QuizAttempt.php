<?php

namespace Harishdurga\LaravelQuiz\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAttempt extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function getTable()
    {
        return config('laravel-quiz.table_names.quiz_attempts');
    }

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

    public function caclculate_score(): float
    {
        $score = 0;
        $quiz_questions_collection = $this->quiz->questions()->with('question')->get();
        $quiz_questions = [];
        $quiz_attempt_answers = [];
        foreach ($quiz_questions_collection as $key => $quiz_question) {
            $question = $quiz_question->question;
            $correct_answer = null;
            if ($question->question_type_id == 1) {
                $correct_answer =  ($question->correct_options())->first()->id;
            } elseif ($question->question_type_id == 2) {
                $correct_answer =  ($question->correct_options())->pluck('id');
            } elseif ($question->question_type_id == 3) {
                $correct_answer = ($question->correct_options())->first()->option;
            } else {
                $correct_answer = null;
            }
            $quiz_questions[$quiz_question->id] = [
                'question_type_id' => $question->question_type_id,
                'is_optional' => $quiz_question->is_optional,
                'marks' => $quiz_question->marks,
                'negative_marks' => $quiz_question->negative_marks,
                'correct_answer' => $correct_answer
            ];
        }
        foreach ($this->answers as $key => $quiz_attempt_answer) {
            $quiz_attempt_answers[$quiz_attempt_answer->quiz_question_id][] = ['option_id' => $quiz_attempt_answer->question_option_id, 'answer' => $quiz_attempt_answer->answer];
        }
        foreach ($quiz_questions as $quiz_question_id => $quiz_question) {
            if ($quiz_question['question_type_id'] == 1) {
                if (!empty($quiz_question['correct_answer'])) {
                    if (isset($quiz_attempt_answers[$quiz_question_id])) {
                        if ($quiz_attempt_answers[$quiz_question_id][0]['option_id'] == $quiz_question['correct_answer']) {
                            $score += $quiz_question['marks'];
                        } else {
                            $score -= $quiz_question['negative_marks'];
                        }
                    } else {
                        if (!$quiz_question['is_optional']) {
                            $score -= $quiz_question['negative_marks'];
                        }
                    }
                } else {
                    if (isset($quiz_attempt_answers[$quiz_question_id])) {
                        $score += $quiz_question['marks'];
                    }
                }
            } elseif ($quiz_question['question_type_id'] == 2) {
                if (!empty($quiz_question['correct_answer'])) {
                    if (isset($quiz_attempt_answers[$quiz_question_id])) {
                        $temp_arr = [];
                        foreach ($quiz_attempt_answers[$quiz_question_id] as $key => $answer) {
                            $temp_arr[] = $answer['option_id'];
                        }
                        if ($quiz_question['correct_answer']->toArray() == $temp_arr) {
                            $score += $quiz_question['marks'];
                        } else {
                            $score -= $quiz_question['negative_marks'];
                        }
                    } else {
                        if (!$quiz_question['is_optional']) {
                            $score -= $quiz_question['negative_marks'];
                        }
                    }
                } else {
                    if (isset($quiz_attempt_answers[$quiz_question_id])) {
                        $score += $quiz_question['marks'];
                    }
                }
            } elseif ($quiz_question['question_type_id'] == 3) {
                if (!empty($quiz_question['correct_answer'])) {
                    if (isset($quiz_attempt_answers[$quiz_question_id])) {
                        if ($quiz_question['correct_answer'] == $quiz_attempt_answers[$quiz_question_id][0]['answer']) {
                            $score += $quiz_question['marks'];
                        } else {
                            $score -= $quiz_question['negative_marks'];
                        }
                    } else {
                        if (!$quiz_question['is_optional']) {
                            $score -= $quiz_question['negative_marks'];
                        }
                    }
                } else {
                    if (isset($quiz_attempt_answers[$quiz_question_id])) {
                        $score += $quiz_question['marks'];
                    }
                }
            } else {
                $score += $quiz_question['marks'];
            }
        }
        return $score;
    }
}
