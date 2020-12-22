<?php

$container->set('googleBooksApi', function ($c) {
    $baseURL = 'https://www.googleapis.com/books/v1/volumes';
    return new Lyncas\Services\GoogleBooksApi($baseURL);
});