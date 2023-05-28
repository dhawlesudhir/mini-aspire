# mini-aspire API

It is an app that allows authenticated users to go through a loan application. It doesn’t have to contain too many fields, but at least “amount required” and “loan term.” All the loans will be assumed to have a “weekly” repayment frequency.

### version required

1 PHP: > 8

2 Laravel: > 9

## Installation

Use the package manager [composer]() to install application.

```bash
composer install
```

## Preusage commands

```bash
php artisan key:generate
php artisan cache:clear
php artisan migrate
```

## Start Local server

```bash
composer artisan serve
```

## Running The Scheduler Locally

Typically, you would not add a scheduler cron entry to your local development machine. Instead, you may use the schedule:work Artisan command. This command will run in the foreground and invoke the scheduler every minute until you terminate the command:

```bash
php artisan schedule:work
```

## Running Tests

```bash
php artisan test
```

## Author

[Sudhir Dhawle](https://www.linkedin.com/in/sudhirdhawle/)
