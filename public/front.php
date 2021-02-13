<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Framework;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$routes = include __DIR__ . "/../src/app.php";

$framework = new Framework($routes);
$response = $framework->handle($request);

$response->send();
