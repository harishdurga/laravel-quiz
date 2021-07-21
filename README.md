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
$topic = Topic::create([
            'topic' => 'Test Topic',
            'slug'=>'test-topic'
            'parent_id'=>$parent_topic->id,
            'is_active'=>true
        ]);
```

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
