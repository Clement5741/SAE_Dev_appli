<?php

require_once "vendor/autoload.php";
use App\classes\dispatch\Dispatcher;
$action = $_GET['action'] ?? null;
$dispatcher = new Dispatcher($action);
$dispatcher->run();
