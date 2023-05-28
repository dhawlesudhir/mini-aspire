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

## Environment setup

1. Make copy .env.example as .env

```bash
.env
.env.example
```

2. Set values for environment variables

EXAMPLE

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=mini_aspire_db
DB_USERNAME=dummyuser
DB_PASSWORD=secret
```

## Preusage commands

```bash
php artisan key:generate
php artisan cache:clear
php artisan migrate
```

## Create Admin(User)

```bash
php artisan db:seed
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

## Credentials

Admin

`email` admin@application.com

`password` admin@appp

PHPMyAdmin

`Username` dummyuser

`Password` secret

## Docker repositor

Seperate GIT repositor has been created.

[dhawlesudhir/mini_aspire_docker_compose_config](https://github.com/dhawlesudhir/mini_aspire_docker_compose_config)

## Documentation

[API](https://documenter.getpostman.com/view/998100/2s93m8xKp3)

## Author

[Sudhir Dhawle](https://www.linkedin.com/in/sudhirdhawle/)
