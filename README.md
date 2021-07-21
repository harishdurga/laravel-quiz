# Laravel Quiz

[![Latest Version on Packagist](https://img.shields.io/packagist/v/harishdurga/laravel-quiz.svg?style=flat-square)](https://packagist.org/packages/harishdurga/laravel-quiz)
[![Total Downloads](https://img.shields.io/packagist/dt/harishdurga/laravel-quiz.svg?style=flat-square)](https://packagist.org/packages/harishdurga/laravel-quiz)
![GitHub Actions](https://github.com/harishdurga/laravel-quiz/actions/workflows/main.yml/badge.svg)

With this package you can easily get quiz functionality into your Laravel project.

## Installation

You can install the package via composer:

```bash
composer require harishdurga/laravel-quiz
```

- Laravel Version: 8.X
- PHP Version: 8.X

## Usage

### Class Diagram
![LaravelQuiz](https://user-images.githubusercontent.com/10380630/126498504-6b0f3956-67c7-47f7-88b1-653b33f9dd77.jpg)


### Publish Vendor Files (config, mingrations,seeder)

```bash
php artisan vendor:publish --provider="Harishdurga\LaravelQuiz\LaravelQuizServiceProvider"
```

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
            'media_type'=>''
        ]);
```

### Add Question To Quiz

```php
$quiz_question =  QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question_id' => $question->id,
            'marks' => 3,
            'order' => 1,
            'negative_marks'=>-1,
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
$quiz_attempt->caclculate_score()
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
