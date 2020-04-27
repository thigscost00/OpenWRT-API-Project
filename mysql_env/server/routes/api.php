<?php

use Illuminate\Http\Request;
use App\Users;

Route::post('/register', 'AuthController@register');

Route::get('/config', 'AuthController@config');

Route::get('/me', 'AuthController@me');
