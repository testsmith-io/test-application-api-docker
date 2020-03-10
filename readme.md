# Digital media store

The Chinook data model represents a digital media store, including tables for artists, albums, media tracks, invoices and customers.

This application can be used for development trainings. The application exists out of different application states to simulate the iterative software development process.

The states a described below:

## Sprint 0
Basic Laravel skeleton project and Chinook database migration and seeding scripts.

## Sprint 1
Basic CRUD API

## Sprint 2
Enhanced API functionality

## Sprint 3
Protected API endpoints

## Sprint 4
Frontend

## Sprint 5


# Generate Swagger documentation
`php artisan swagger-lume:generate`

# Run unittests
`./vendor/bin/phpunit`

# Start project locally
`php -S localhost:8000 -t public`

# How to use this project

Install docker and run the following commands:

`docker-compose up -d`

Use the following command line options:

`-d` Detached mode: Run container in the background, print new container name.
`--force-recreate` Recreate containers even if their configuration and image haven't changed.


## Migrate database
`docker-compose exec app php artisan migrate`

## Seed database
`docker-compose exec app php artisan db:seed`

## phpmyadmin
`http://localhost:8000`

Username `root` and password `root`

## application
`http://localhost:8091`

## Swagger documentation
/api/documentation

## Routes
/api/albums
/api/artists
/api/customers
/api/employees
/api/genres
/api/invoices
/api/invoicelines
/api/mediatypes
/api/playlists
/api/tracks
