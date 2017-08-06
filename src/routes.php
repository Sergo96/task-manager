<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/login/', 'AuthController@getLoginPage');
SimpleRouter::post('/login/', 'AuthController@login');

SimpleRouter::get('/create/', 'TaskController@createTask');
SimpleRouter::get('/', 'TaskController@getTasksList');
