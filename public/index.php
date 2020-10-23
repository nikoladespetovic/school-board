<?php

require_once "../vendor/autoload.php";

session_start();

include_once '../config/routes.php';


// Initial route
\App\Logic\Route::run();

