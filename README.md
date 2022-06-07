![Laravel Quiz](https://user-images.githubusercontent.com/10380630/172395768-968e3b09-a286-4643-943d-fb55a34e0095.png)

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

![LaravelQuiz](https://user-images.githubusercontent.com/10380630/172040172-c6bc4783-98f1-4784-8b78-2060ee8e0936.jpg)

### Publish Vendor Files (config, mingrations,seeder)

```bash
php artisan vendor:publish --provider="Harishdurga\LaravelQuiz\LaravelQuizServiceProvider"
```

If you are updating the package, you may need to run the above command to publish the vendor files. But please take a backup of the config file. Also run the migration command to add new columns to the existing tables.

### Create Topic

```php
    $computer_science = Topic::create([
        'topic' => 'Computer Science',
        'slug' => 'computer-science',
    ]);
```

#### Create Sub Topics

```php
    $algorithms = Topic::create([
            'topic' => 'Algorithms',
            'slug' => 'algorithms'
        ]);
        $computer_science->children()->save($algorithms);
```

### Question Types

A seeder class `QuestionTypeSeeder ` will be publsihed into the `database/seeders` folder. Run the following command to seed question types.

```bash
php artisan db:seed --class=QuestionTypeSeeder
```

Currently this package is configured to only handle the following type of questions

- `multiple_choice_single_answer`
- `multiple_choice_multiple_answer`
- `fill_the_blank`
  Create a QuestionType:

```php
QuestionType::create(['question_type'=>'select_all']);
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
            'question' => 'What is an algorithm?',
            'question_type_id' => 1,
            'is_active' => true,
            'media_url'=>'url',
            'media_type'=>'image'
        ]);
```

### Fecth Questions Of A Question Type

```php
$question_type->questions
```

### Attach Topics To Question

```php
$question->topics()->attach([$computer_science->id, $algorithms->id]);
```

### Question Option

```php
$question_two_option_one = QuestionOption::create([
            'question_id' => $question_two->id,
            'option' => 'array',
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
            'title' => 'Computer Sceince Quiz',
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

### Negative Marking Settings

By default negative marking is enabled for backward compatibility. You can disable it by setting the `enable_negative_marks` to false. Two types of negative marking are supported(`negative_marking_type`). `fixed` and `percentage`. Negative marking value defined at question level will be given precedence over the value defined at quiz level. If you want to set the negative marking value at quiz level, set the `negative_mark_value` to the value you want to set. If you want to set the negative marking value at question level, set the `negative_marks` of `QuizQuestion` to your desired value. No need to give a negative number instead the negative marks or percentage should be given in positive.

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
