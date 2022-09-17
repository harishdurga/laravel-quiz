![Laravel Quiz](https://user-images.githubusercontent.com/10380630/172400217-b9192a50-3227-4d30-8e00-7a301fe68ddc.png)

# Laravel Quiz

[![Latest Version on Packagist](https://img.shields.io/packagist/v/harishdurga/laravel-quiz.svg?style=flat-square)](https://packagist.org/packages/harishdurga/laravel-quiz)
[![Total Downloads](https://img.shields.io/packagist/dt/harishdurga/laravel-quiz.svg?style=flat-square)](https://packagist.org/packages/harishdurga/laravel-quiz)
![GitHub Actions](https://github.com/harishdurga/laravel-quiz/actions/workflows/main.yml/badge.svg)

With this package you can easily get quiz functionality into your Laravel project.

## Features

- Add Topics to Questions, Quizzes and to other Topics
- Supported Question Types: Multiple Choice,Single Choice, and Fill In The Blank
- Add your own Question Types and define your own methods to handle them
- Flexible Negative Marking Settings
- Flexible Quiz with most of the useful settings (Ex: Total marks, Pass marks, Negative Marking, Duration, Valid between date, Description etc)
- Any type of User of your application can be a Participant of a Quiz
- Any type of User, and any number of Users of your application can be Authors (different roles) For a Quiz
- Generate Random Quizzes (In progress)

## Installation

You can install the package via composer:

```bash
composer require harishdurga/laravel-quiz
```

- Laravel Version: 9.X
- PHP Version: 8.X

## Usage

### Class Diagram

![LaravelQuiz](https://user-images.githubusercontent.com/10380630/182762726-de5d4b61-af3c-4d0f-b25d-dad986ff5b6e.jpg)

### Publish Vendor Files (config, mingrations,seeder)

```bash
php artisan vendor:publish --provider="Harishdurga\LaravelQuiz\LaravelQuizServiceProvider"
```

If you are updating the package, you may need to run the above command to publish the vendor files. But please take a backup of the config file. Also run the migration command to add new columns to the existing tables.

### Create Topic

```php
$computer_science = Topic::create([
    'name' => 'Computer Science',
    'slug' => 'computer-science',
]);
```

#### Create Sub Topics

```php
$algorithms = Topic::create([
    'name' => 'Algorithms',
    'slug' => 'algorithms'
]);
$computer_science->children()->save($algorithms);
```

### Question Types

A seeder class `QuestionTypeSeeder ` will be published into the `database/seeders` folder. Run the following command to seed question types.

```bash
php artisan db:seed --class=\\Harishdurga\\LaravelQuiz\\Database\\Seeders\\QuestionTypeSeeder
```

Currently this package is configured to only handle the following type of questions

- `multiple_choice_single_answer`
- `multiple_choice_multiple_answer`
- `fill_the_blank`

Create a QuestionType:

```php
QuestionType::create(['name'=>'select_all']);
```

### User Defined Methods To Evaluate The Answer For Each Question Type

Though this package provides three question types you can easily change the method that is used to evaluate the answer. You can do this by updating the `get_score_for_question_type` property in config file.

```php
'get_score_for_question_type' => [
    1 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_1_question',
    2 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_2_question',
    3 => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::get_score_for_type_3_question',
    4 => 'Your custom method'
]
```

But your method has needs to have the following signature

```php
/**
 * @param QuizAttemptAnswer[] $quizQuestionAnswers All the answers of the quiz question
 */
public static function get_score_for_type_3_question(QuizAttempt $quizAttempt, QuizQuestion $quizQuestion, array $quizQuestionAnswers, $data = null): float
{
    // Your logic here
}
```

If you need to pass any data to your method then you can pass it as the last `$data` parameter. When you call the `caclculate_score()` method of `QuizAttempt` then you can pass the data as the parameter.

### Create Question

```php
$question_one = Question::create([
    'name' => 'What is an algorithm?',
    'question_type_id' => 1,
    'is_active' => true,
    'media_url'=>'url',
    'media_type'=>'image'
]);
```

### Fetch Questions Of A Question Type

```php
$question_type->questions
```

### Fetch only questions with an option (valid question)

```php
Question::hasOptions()->get()
```

### Attach Topics To Question

```php
$question->topics()->attach([$computer_science->id, $algorithms->id]);
```

### Question Option

```php
$question_two_option_one = QuestionOption::create([
            'question_id' => $question_two->id,
            'name' => 'array',
            'is_correct' => true,
            'media_type'=>'image',
            'media_url'=>'media url'
        ]);
```

### Fetch Options Of A Question

```php
$question->options
```

### Create Quiz

```php
$quiz = Quiz::create([
    'name' => 'Computer Science Quiz',
    'description' => 'Test your knowledge of computer science',
    'slug' => 'computer-science-quiz',
    'time_between_attempts' => 0, //Time in seconds between each attempt
    'total_marks' => 10,
    'pass_marks' => 6,
    'max_attempts' => 1,
    'is_published' => 1,
    'valid_from' => now(),
    'valid_upto' => now()->addDay(5),
    'media_url'=>'',
    'media_type'=>'',
    'negative_marking_settings'=>[
        'enable_negative_marks' => true,
        'negative_marking_type' => 'fixed',
        'negative_mark_value' => 0,
    ]
]);
```

### Attach Topics To A Quiz

```php
$quiz->topics()->attach([$topic_one->id, $topic_two->id]);
```

### Topicable

Topics can be attached to a quiz or a question. Questions can exist outside of the quiz context. For example you can create a question bank which you can filter based on the topics if attached.

### Negative Marking Settings

By default negative marking is enabled for backward compatibility. You can disable it by setting the `enable_negative_marks` to false. Two types of negative marking are supported(`negative_marking_type`). `fixed` and `percentage`. Negative marking value defined at question level will be given precedence over the value defined at quiz level. If you want to set the negative marking value at quiz level, set the `negative_mark_value` to the value you want to set. If you want to set the negative marking value at question level, set the `negative_marks` of `QuizQuestion` to your desired value. No need to give a negative number instead the negative marks or percentage should be given in positive.

### Adding An Author(s) To A Quiz

```php
$admin = Author::create(
            ['name' => "John Doe"]
        );
$quiz = Quiz::factory()->make()->create([
            'name' => 'Sample Quiz',
            'slug' => 'sample-quiz'
        ]);
QuizAuthor::create([
            'quiz_id' => $quiz->id,
            'author_id' => $admin->id,
            'author_type' => get_class($admin),
            'author_role' => 'admin',
        ]);
$quiz->quizAuthors->first()->author; //Original User
```

Add `CanAuthorQuiz` trait to your model and you can get all the quizzes associated by calling the `quizzes` relation. You can give any author role you want and implement ACL as per your usecase.

### Add Question To Quiz

```php
$quiz_question =  QuizQuestion::create([
    'quiz_id' => $quiz->id,
    'question_id' => $question->id,
    'marks' => 3,
    'order' => 1,
    'negative_marks'=>1,
    'is_optional'=>false
]);
```

### Fetch Quiz Questions

```php
$quiz->questions
```

### Attempt The Quiz

```php
$quiz_attempt = QuizAttempt::create([
    'quiz_id' => $quiz->id,
    'participant_id' => $participant->id,
    'participant_type' => get_class($participant)
]);
```

### Get the Quiz Attempt Participant

`MorphTo` relation.

```php
$quiz_attempt->participant
```

### Answer Quiz Attempt

```php
QuizAttemptAnswer::create(
    [
        'quiz_attempt_id' => $quiz_attempt->id,
        'quiz_question_id' => $quiz_question->id,
        'question_option_id' => $question_option->id,
    ]
);
```

A `QuizAttemptAnswer` belongs to `QuizAttempt`,`QuizQuestion` and `QuestionOption`

### Get Quiz Attempt Score

```php
$quiz_attempt->calculate_score()
```

In case of no answer found for a quiz question which is not optional, a negative score will be applied if any.

### Get Correct Option Of A Question

```php
$question->correct_options
```

Return a collection of `QuestionOption`.

```php
public function correct_options(): Collection
{
    return $this->options()->where('is_correct', 1)->get();
}
```

Please refer unit and features tests for more understanding.

### Validate A Quiz Question
Instead of getting total score for the quiz attempt, you can use `QuizAttempt` model's `validate()` method. This method will return an array with a QuizQuestion model's 
`id` as the key for the assoc array that will be returned.
**Example:**
```injectablephp
$quizAttempt->validate($quizQuestion->id); //For a particular question
$quizAttempt->validate(); //For all the questions in the quiz attempt
$quizAttempt->validate($quizQuestion->id,$data); //$data can any type
```
```php
[
  1 => [
    'score' => 10,
    'is_correct' => true,
    'correct_answer' => ['One','Five','Seven'],
    'user_answer' => ['Five','One','Seven']
  ],
  2 => [
    'score' => 0,
    'is_correct' => false,
    'correct_answer' => 'Hello There',
    'user_answer' => 'Hello World'
  ]
]
```
To be able to render the user answer and correct answer for different types of question types other than the 3 types supported by the package, a new config option has been added.
```php
'render_answers_responses'    => [
        1  => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::renderQuestionType1Answers',
        2  => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::renderQuestionType2Answers',
        3  => '\Harishdurga\LaravelQuiz\Models\QuizAttempt::renderQuestionType3Answers',
    ]
```
By keeping the question type id as the key, you can put the path to your custom function to handle the question type. This custom method will be called from inside the 
`validate()` method by passing the `QuizQuestion` object as the argument for your custom method as defined in the config.
**Example:**
```php
public static function renderQuestionType1Answers(QuizQuestion $quizQuestion, mixed $data=null)
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
```
As shown in the example you customer method should return an array with two elements the first one being the correct answer and the second element being the user's answer for the question.
And whatever the `$data` you send to the `validate()` will be sent to these custom methods so that you can send additional data for rendering the answers.

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email durgaharish5@gmail.com instead of using the issue tracker.

## Credits

- [Harish Durga](https://github.com/harishdurga)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
