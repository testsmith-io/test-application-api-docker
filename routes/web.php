<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/customers'], function () use ($router) {
    $router->post('login', ['uses' => 'CustomerController@login']);
    $router->get('logout', ['uses' => 'CustomerController@logout']);
    $router->get('refresh', ['uses' => 'CustomerController@refresh']);
    $router->get('me', ['uses' => 'CustomerController@me']);
});

$router->group(['prefix' => 'api/employees'], function () use ($router) {
    $router->post('login', ['uses' => 'EmployeeController@login']);
    $router->get('logout', ['uses' => 'EmployeeController@logout']);
    $router->get('refresh', ['uses' => 'EmployeeController@refresh']);
    $router->get('me', ['uses' => 'EmployeeController@me']);
});

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('albums', ['uses' => 'AlbumController@index']);
    $router->get('albums/{id}', ['uses' => 'AlbumController@show']);
    $router->post('albums', ['uses' => 'AlbumController@store']);
    $router->delete('albums/{id}', ['uses' => 'AlbumController@destroy']);
    $router->put('albums/{id}', ['uses' => 'AlbumController@update']);

    $router->get('artists', ['uses' => 'ArtistController@index']);
    $router->get('artists/{id}', ['uses' => 'ArtistController@show']);
    $router->post('artists', ['uses' => 'ArtistController@store']);
    $router->delete('artists/{id}', ['uses' => 'ArtistController@destroy']);
    $router->put('artists/{id}', ['uses' => 'ArtistController@update']);

    $router->get('customers', ['uses' => 'CustomerController@index']);
    $router->get('customers/{id}', ['uses' => 'CustomerController@show']);
    $router->post('customers', ['uses' => 'CustomerController@store']);
    $router->delete('customers/{id}', ['uses' => 'CustomerController@destroy']);
    $router->put('customers/{id}', ['uses' => 'CustomerController@update']);

    $router->get('employees', ['uses' => 'EmployeeController@index']);
    $router->get('employees/{id}', ['uses' => 'EmployeeController@show']);
    $router->post('employees', ['uses' => 'EmployeeController@store']);
    $router->delete('employees/{id}', ['uses' => 'EmployeeController@destroy']);
    $router->put('employees/{id}', ['uses' => 'EmployeeController@update']);

    $router->get('genres', ['uses' => 'GenreController@index']);
    $router->get('genres/{id}', ['uses' => 'GenreController@show']);
    $router->post('genres', ['uses' => 'GenreController@store']);
    $router->delete('genres/{id}', ['uses' => 'GenreController@destroy']);
    $router->put('genres/{id}', ['uses' => 'GenreController@update']);

    $router->get('invoices', ['uses' => 'InvoiceController@index']);
    $router->get('invoices/{id}', ['uses' => 'InvoiceController@show']);
    $router->post('invoices', ['uses' => 'InvoiceController@store']);
    $router->delete('invoices/{id}', ['uses' => 'InvoiceController@destroy']);
    $router->put('invoices/{id}', ['uses' => 'InvoiceController@update']);

    $router->get('invoicelines', ['uses' => 'InvoicelineController@index']);
    $router->get('invoicelines/{id}', ['uses' => 'InvoicelineController@show']);
    $router->post('invoicelines', ['uses' => 'InvoicelineController@store']);
    $router->delete('invoicelines/{id}', ['uses' => 'InvoicelineController@destroy']);
    $router->put('invoicelines/{id}', ['uses' => 'InvoicelineController@update']);

    $router->get('mediatypes', ['uses' => 'MediatypeController@index']);
    $router->get('mediatypes/{id}', ['uses' => 'MediatypeController@show']);
    $router->post('mediatypes', ['uses' => 'MediatypeController@store']);
    $router->delete('mediatypes/{id}', ['uses' => 'MediatypeController@destroy']);
    $router->put('mediatypes/{id}', ['uses' => 'MediatypeController@update']);

    $router->get('playlists', ['uses' => 'PlaylistController@index']);
    $router->get('playlists/{id}', ['uses' => 'PlaylistController@show']);
    $router->post('playlists', ['uses' => 'PlaylistController@store']);
    $router->delete('playlists/{id}', ['uses' => 'PlaylistController@destroy']);
    $router->put('playlists/{id}', ['uses' => 'PlaylistController@update']);

    $router->get('tracks', ['uses' => 'TrackController@index']);
    $router->get('tracks/{id}', ['uses' => 'TrackController@show']);
    $router->post('tracks', ['uses' => 'TrackController@store']);
    $router->delete('tracks/{id}', ['uses' => 'TrackController@destroy']);
    $router->put('tracks/{id}', ['uses' => 'TrackController@update']);

});
