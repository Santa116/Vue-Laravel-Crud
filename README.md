# Laravel Vue CRUD

It's simple crud operation using vue in laravel.

#What is covered in repo:

	- Add/Update/View/Delete Post
	- Pagination
	- Filter by active/deleted post
	- Restore data which was deleted(softdelete)

#
# Installation

When you clone this project cd into directory and then:

 - Copy .env.example to .env
 - `composer install`
 - `chmod -R 777 storage/ bootstrap/`
 - `php artisan key:generate`
 - Fill .env file with database credentials and upload paths.
 - `php artisan migrate`
   
Now you are all set. 
