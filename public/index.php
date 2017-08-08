<?php

use Pecee\SimpleRouter\SimpleRouter;

require_once(__DIR__ . '/../src/bootstrap.php');

SimpleRouter::setDefaultNamespace('\ToDo\Controllers');

require_once(__DIR__ . '/../src/routes.php');

SimpleRouter::start();

unset($_SESSION['notifications']);
