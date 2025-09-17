
## ALL CHANGES I MADE ARE MARKED WITH 天使

## I added Laravel Jetstream for Authentication

composer require laravel/jetstream

php artisan jetstream:install livewire
- run migrations? no

npm install && npm run dev

php artisan migrate


## I created these files

## Migration Files
database\migrations\2025_09_03_143445_add_usertype_column.php
- this is for adding usertype column

## Middleware
app\Http\Middleware\EnsureUserHasRole.php
- this is to ensure user has a role

## Controller
app\Http\Controllers\HomeController.php
- jetstream's redirect to landing page

## Seeder
database\seeders\AdminUserSeeder.php
- to make an Admin Account


## I edited these files

app\Models\User.php
- I added role checking usertype column


## !! I DELETED THESE FILES(moved to backup/)

LoginController.php - already using Fortify by Jetstream
StaticAuth.php - already using Fortify by Jetstream