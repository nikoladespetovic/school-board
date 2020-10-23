<?php

use App\Logic\Route;

// Route for student page
Route::get('/student/(.*)', 'StudentsController@getStudent');

