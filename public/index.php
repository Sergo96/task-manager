<?php

require_once('../src/bootstrap.php');

(new \ToDo\Controllers\ToDo(\ToDo\Helpers\Container::getInstance()))->getTasksList();