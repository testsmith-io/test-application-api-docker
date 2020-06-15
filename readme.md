# Digital media store

The Chinook data model represents a digital media store, including tables for artists, albums, media tracks, invoices and customers.

This application can be used for development trainings. The application exists out of different application states to simulate the iterative software development process.

The states a described below:

## Sprint 0
Basic Laravel skeleton project and Chinook database migration and seeding scripts. You can get a good understanding of the database, data structures, foreign keys.

## Sprint 1
Basic CRUD (Create, Read, Update, Delete) API for the following endpoints:

- `/api/albums`
- `/api/artists`
- `/api/customers`
- `/api/employees`
- `/api/genres`
- `/api/invoices`
- `/api/invoicelines`
- `/api/mediatypes`
- `/api/playlists`
- `/api/tracks`

As from this branch the project implemented [Swagger](https://swagger.io/) and PHPUnit tests.

## Sprint 2
Protected API endpoints. An employee can only query related customers. A customer can only query its own invoices. We can use the following endpoints:

- `/api/customers/login`
- `/api/customers/logout`
- `/api/customers/refresh`
- `/api/customers/me`

- `/api/employees/login`
- `/api/employees/logout`
- `/api/employees/refresh`
- `/api/employees/me`

Both `login` and `refresh` return a token which can be used when calling the protected API's. We use a `Bearer` token on the `Authorization` header.

This branch contains PHPUnit tests for the protected endpoints. 

## Sprint 3
Enhanced API functionality. This branch implements reporting queries using the following endpoints:

- `/api/reports/countCustomersBySalesAgent`
- `/api/reports/totalSalesPerCountry`
- `/api/reports/totalSalesPerSalesAgent`
- `/api/reports/top10PurchasedTracks`
- `/api/reports/top5BestSellingArtists`
- `/api/reports/topArtistByGenre`
- `/api/reports/totalSalesOfYear`
- `/api/reports/customersByCountry`

This branch implements up-to-date Swagger.

## Sprint 4
This branch implements pagination to all the `GET` API calls.


# How to use this project

## Step 1: start containers
Install docker and run the following commands:

`docker-compose up -d`

Use the following command line options:

`-d` Detached mode: Run container in the background, print new container name.
`--force-recreate` Recreate containers even if their configuration and image haven't changed.


## Step 2: Migrate database
`docker-compose exec app php artisan migrate`

## Step 3: Seed database
`docker-compose exec app php artisan db:seed`


#URL's

## API application
`http://localhost:8091`

## phpMyAdmin
`http://localhost:8000`

Username `root` and password `root`

## Swagger documentation
`http://localhost:8091/api/documentation`


# FAQ

**Q**: What if I get the following error: `SQLSTATE[HY000] [2002] Connection refused` while executing the migrations?

**A**: Just wait a few seconds and try again.

 
**Q**: What if I get strange errors while switching branches?

**A**: Try to execute the following commands: `docker-compose down` and then `docker-compose up -d`

# Extra information

## Generate Swagger documentation
`docker-compose exec app php artisan swagger-lume:generate`

## Start project locally
`php -S localhost:8000 -t public`

## Run unittests
`./vendor/bin/phpunit`
