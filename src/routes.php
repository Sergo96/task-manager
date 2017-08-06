<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/login/', 'AuthController@getLoginPage');
SimpleRouter::post('/login/', 'AuthController@login');

SimpleRouter::get('/create/', 'TaskController@createTask');

SimpleRouter::get('/task/{id}', 'TaskController@taskByIdAction', [
    'where' => ['id' => '[0-9]+'],
]);
SimpleRouter::get('/{page}', 'TaskController@getTasksList', [
    'where' => ['page' => '[0-9]?'],
]);