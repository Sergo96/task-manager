<?php

use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::get('/login/', 'AuthController@tasksListAction');
SimpleRouter::post('/login/', 'AuthController@login');

SimpleRouter::get('/create/', 'TaskController@createTaskAction');
SimpleRouter::post('/create/', 'TaskController@createTask', [
    
]);

SimpleRouter::get('/task/{id}', 'TaskController@taskByIdAction', [
    'where' => ['id' => '[0-9]+'],
]);
SimpleRouter::get('/{page?}', 'TaskController@tasksListAction', [
    'where' => ['page' => '[0-9]+'],
]);
