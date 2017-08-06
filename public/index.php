<?php

require_once('../src/bootstrap.php');

(new \ToDo\Controllers\TaskController(\ToDo\Helpers\Container::getInstance()))->getTasksList();