# NutriCQ - Backend Capstone Project

NutriCQ is a web API based application that can assist users in 
controlling and tracking their nutritional intake, 
as well as assisting users in answering their questions 
or complaints related to nutrition by consulting directly
with a nutritionist. With that, users will be more 
educated and more concerned with their nutritional 
adequacy.

The creation of NutriCQ application came from a problem, 
where based on a 2014 study conducted by the Ministry of 
Health, unhealthy diet patterns due to unfulfilled balanced 
nutrition are still a problem in Indonesia. Referring to 
the study, it is also known that the ratio of consumption 
of vegetable and animal protein is still not balanced, 
thus affecting the quality of people's food.

## Features
This application has 3 user sides, namely user, admin, and doctor. The following are the features of the NutriCQ application:
- User authentication (login and register)
- User profile
- Record daily nutritional goals of users
- Calculate the nutrition of a food
- Record the nutritional needs of users
- Record all nutritional intake inputted by the user
- Users can consult a nutritionist
- Users can make a direct consultation booking with a nutritionist
- Doctors can approve bookings from users and serve consultations
- Users can change their daily nutritional needs as desired
- Admin can add, change, and delete food data
- Admin can approve prospective doctors who register in this application

## Tech Stack
* PHP : `7.4`
* Laravel : `8`
* Database : `MySql`
* Laratrust : `7.1`
* Sanctum auth : `2.11`
* PHPUnit : `9.5`
* fakerphp : `1.9.1`

## How to Run

If you want to run this application locally, you can follow these steps:
1. Clone this repository
 ```
git clone https://github.com/divawidia/capstone-project.git
```
2. Update composer module
```
composer update
```
3. Rename file `.env.example` to `.env`
4. Create new database in MySql according to the database name in `DB_DATABASE` which is in the `.env` . file
5. Run the database migration and seeder 
```
php artisan migrate --seed
```
6. Run file storage link configuration
```
php artisan storage:link
```
7. Import food data which in `foodData.sql` file to the created database
8. Set app key
```
php artisan key:generate
```
8. Run the project
```
php artisan serve
```
9. navigate to `http://127.0.0.1:8000/api/`

## API Documentation
The API documentation of the NutriCQ application can be seen in the following Postman collection:
