# Laravel Project

<p align="center"> <a href="https://laravel.com" target="_blank"> <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"> </a> </p>

<p align="center"> <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a> <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a> <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a> <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a> </p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](<https://laravel.com/docs/routing>).
- [Powerful dependency injection container](<https://laravel.com/docs/container>).
- Multiple back-ends for [session](<https://laravel.com/docs/session>) and [cache](<https://laravel.com/docs/cache>) storage.
- Expressive, intuitive [database ORM](<https://laravel.com/docs/eloquent>).
- Database agnostic [schema migrations](<https://laravel.com/docs/migrations>).
- [Robust background job processing](<https://laravel.com/docs/queues>).
- [Real-time event broadcasting](<https://laravel.com/docs/broadcasting>).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Installation

Follow these steps to set up the project on your local machine.

### 1. Clone the Repository

```
git clone https://github.com/ariprw/laravel-13
cd laravel-13
```

### 2. Install Dependencies

```
composer install
```

### 3. Configure the Environment

Copy the example environment file:

```
cp .env.example .env
```

Open the `.env` file and configure your database connection:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Adjust these values according to your local database configuration.

### 4. Create the Database

Create a new database using your database management tool, such as MySQL, phpMyAdmin, or the MySQL command line.

Make sure the database name matches the `DB_DATABASE` value in your `.env` file.

### 5. Generate the Application Key

```
php artisan key:generate
```

### 6. Run Database Migrations

```
php artisan migrate
```

This command creates the database tables defined by the project's migrations.

### 7. Start the Development Server

```
php artisan serve
```

Open the application in your browser at:

```
http://127.0.0.1:8000
```

## Learning Laravel

Laravel has extensive [documentation](<https://laravel.com/docs>) and a video tutorial library to help you get started.

[Laracasts](<https://laracasts.com>) contains thousands of video tutorials covering Laravel, modern PHP, unit testing, and JavaScript.

You can also explore [Laravel Learn](<https://laravel.com/learn>) for guided lessons and practical projects.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot.

Install [Laravel Boost](<https://laravel.com/docs/ai>) to enhance your AI-assisted development workflow:

```
composer require laravel/boost --dev

php artisan boost:install
```

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](<https://laravel.com/docs/contributions>).

## Code of Conduct

To ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](<https://laravel.com/docs/contributions#code-of-conduct>).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please refer to the official [Laravel security documentation](<https://laravel.com/docs>).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](<https://opensource.org/licenses/MIT>).
