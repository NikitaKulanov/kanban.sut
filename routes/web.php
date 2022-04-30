<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return 1;
});

Route::get('/information', function (){
    return 'This is the API web-server sut. Your client must be able to work with the application/json format.';
})->name('information');

Route::get('/unauthenticated', function (){
    return 'Unauthenticated!';
})->name('unauthenticated');

Route::get('/', function () {
    return view('welcome');
});
