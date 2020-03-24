# shop-admin
A basic online shop with a simple admin section for users and products (Laravel and VueJS).

# Installation
* Clone the project

Run the following commands:
* npm install
* composer install
* cp .env.example .env

- Create a new mysql database called shop_admin
- Change the credentials for the database on the .env file (user/password)

Run migrations:
- php artisan migrate

Run passport install:
- php artisan passport:install

Run seeders:
- php artisan db:seed

Run server:
- php artisan serve