<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/login/', 'AuthController@loginPageAction');
SimpleRouter::post('/login/', 'AuthController@login');

SimpleRouter::get('/create/', 'TaskController@createTaskAction');
SimpleRouter::post('/create/', 'TaskController@createTask', [
    
]);

SimpleRouter::get('/task/{id}', 'TaskController@taskByIdAction', [
    'where' => ['id' => '[0-9]+'],
]);
SimpleRouter::get('/{page?}/{search_by?}{search_string?}', 'TaskController@tasksListAction', [
    'where' => ['page' => '[0-9]+'],
]);
