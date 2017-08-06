<?php

use Pecee\SimpleRouter\SimpleRouter;

require_once('../src/bootstrap.php');

SimpleRouter::setDefaultNamespace('\ToDo\Controllers');

require_once('../src/routes.php');

SimpleRouter::start();

unset($_SESSION['notifications']);
