<?php

ini_set('display_errors', 1); // TODO remove

session_start();

define('ITEMS_PER_PAGE', 3);
define('MAX_PAGES_SHOW', 5);

require_once('../vendor/autoload.php');
