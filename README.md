<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Getting Started
## Install dependencies
#### `composer install`

This script is going to install dependencies required to make this app work.

## Database configuration
When `composer install` finishes, open file .env and set your database configuration. In my case, I used MySQL with [XAMPP](https://www.apachefriends.org/es/index.html)

## Create database and seeders
Once you set up your database, we are going to create the databases required and seed it with fake users and one user that I set to test this app. So, open your terminal, make sure that you are in the app directory and type the next command:
#### `php artisan migrate:refresh --seed`

## Run the app
Open your terminal, make sure that you are in the app directory and type the next command to run the server:
#### `php artisan serve`

Your server address is [http://localhost:8000](http://localhost:8000).

## How it is work
Once you have the server running, follow the [Protest-test-frontend](https://github.com/jmrona/protected-test-frontend) instruction and when you see the login screen, use the `username: protected` and `password: protected` to sign in to the app
## Learn more

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.
