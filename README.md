# Course Portal

## Project Overview
This project was written using PHP (Laravel) it builds upon the template code earlier provided. 

## Project setup

This project built with Laravel follows the regular convention in its setup. 

Run `composer install` to install dependencies. 

To use the project you need to run migrations and then seed the database. Run migrations by typing this command in the terminal `php artisan migrate` .

Seeding of the database can be done using this command `php artisan db:seed`. This will setup the database for first use. 


 ### Running Tests
 SQLite is used as the database of choice when running tests. 
 
 Create an sqlite database in the `database` folder with the name `test.sqlite`

 To run tests simply run the command `composer test`


## Note
You can run a custom artisan command that creates a "comment entered" record and "lesson watched" record, by typing this command `php artisan triggerEvent`

