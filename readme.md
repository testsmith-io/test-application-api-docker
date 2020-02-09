# DVD rental Application

This application can be used for development trainings. The application exists out of different application states to simulate the iterative software development process.

The states a described below:

## Sprint 0
Basic Laravel skeleton project and Sakila database migration and seeding scripts.

## Sprint 1
Basic CRUD API

## Sprint 2
Enhanced API functionality

## Sprint 3
Protected API endpoints

## Sprint 4
Vue frontend

## Sprint 5


# How to use this project

Install docker and run the following commands:

`docker-compose up`

Use the following command line options:

`-d` Detached mode: Run container in the background, print new container name.
`--force-recreate` Recreate containers even if their configuration and image haven't changed.


## Migrate database
`docker-compose exec app php artisan migrate`

## Seed database
`docker-compose exec app php artisan db:seed`


## Applications

| Port  | Application                                   |
| :---  | :---                                          |
| 8000  | phpmyadmin (username: root / password: root)  |
| 8091  | REST API                                      |
| 4200  | Angular frontend                              |


## Update host file
API-calls will be done from the browser, so they need to be routed to the correct IP address.

### Windows

C:/Windows/System32/Drivers/Etc/hosts

Add this line `<ip-of-machine-running-the-application>        web`

### Unix / macOS

/etc/hosts

Add this line `<ip-of-machine-running-the-application>        web`
