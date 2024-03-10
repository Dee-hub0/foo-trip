### Prerequisites

*Prerequisites for the proper functioning of this project:
- PHP Version 8.2.0
- Symfony version 7.0.5
- MySQL 5.7
- Composer

### Installation

After cloning the project into exp. folder: 'foo_trip'

1- ``composer install`` in order to install all the composer dependencies of the project.

2- install the MySQL database.

    To configure the creation of your database, go to the project's .env file, and modify the environment variable according to your settings:

     DATABASE_URL="mysql://user:password@127.0.0.1:3306/DB_NAME?serverVersion=8.0.32&charset=utf8mb4"

    Then execute the creation of the database with the command:

      ``symfony console doctrine:database:create``

3- Run the migration in the database: ``symfony console doctrine:migration:migrate`

4- Manually Create an admin user inside the User Table, with the role ["ROLE_ADMIN"], use this command to   hash the password :  ``symfony console security:hash-password``

### Startup

Home Page : “Url”/

Administration Authentication Area : “Url”/login
    