
### What is this repository for? ###

* This is a test application

### How do I get set up? ###

* git clone https://github.com/sondave/medbok-dev-app.git
* Create an empty database
* navigate to app-files folder on /medbok-dev-app/app-files
* run cp .env.example .env and update database configurations for the application in the .env file 
* run php artisan migrate 
* run composer install command in your application root folder
* run php artisan key:generate
