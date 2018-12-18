# ToDo List App

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0514a75d8ea94909bfaf427f42e69573)](https://www.codacy.com/app/ffouillet/Todo-Co?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ffouillet/Todo-Co&amp;utm_campaign=Badge_Grade) <a href="https://codeclimate.com/github/ffouillet/Todo-Co/maintainability"><img src="https://api.codeclimate.com/v1/badges/b5772ac72d245785f994/maintainability" /></a>  

ToDoList application built with **Symfony 4.1.7**  

## Installation

1.  Clone (or download) the repository on your local machine. Run this command to clone the repository :  ```git clone https://github.com/ffouillet/Todo-Co.git ```

2.  Install project dependencies by running following command in the project directory : ```composer install```.

3.  Edit your parameters like database parameters in ```.env``` file at the project root.

4.  Create the database and update the database schema by running following commands (always in the project directory) :   
```php bin/console doctrine:database:create```  
```php bin/console doctrine:schema:create```  

5.  Your project is ready, open your browser and go to the server url pointing to your project.

6.  This project requires at least one user to use it.  
You can load fixtures (initial set of test datas) if you want in order to test the project.  
Fixtures are only two users (an user with ```ROLE_ADMIN``` and another with ```ROLE_USER```), see [UserFixtures](https://github.com/ffouillet/Todo-Co/blob/master/src/DataFixtures/UserFixtures.php) for details.
To load fixtures, run that command at the project root :  
``` php bin/console doctrine:fixtures:load ```

## Unit and functional tests
This project contains unit and functional tests, code coverage is above 80.00%, [this PR shows code coverage](https://github.com/ffouillet/Todo-Co/pull/16).  
Run unit and functional tests by executing following commands at the project root :  
``` php bin/console doctrine:schema:create --env=test``` (Create test environment DB)  
``` php bin/console doctrine:schema:update --env=test```  
``` ./bin/phpunit ```

## Documentation

This project includes :
*   [Technical documentation about authentication](https://github.com/ffouillet/Todo-Co/blob/master/doc/Authentication.md)
*   [Application Diagrams](https://github.com/ffouillet/Todo-Co/tree/master/diagrams)
*   [Quality audit and application performance analysis](Todo)

## Contributing to the project  

Please check [CONTRIBUTING.md](https://github.com/ffouillet/Todo-Co/blob/master/CONTRIBUTING.md) if you want to contribute to the project.
