<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Event\ResponseEvent;
use App\Framework;
use App\Listener\ContentLengthListener;
use App\Listener\GoogleListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;

$request = Request::createFromGlobals();
$routes = include __DIR__ . "/../src/app.php";

$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new ContentLengthListener());
$dispatcher->addSubscriber(new GoogleListener());

$framework = new Framework($routes);
$framework->setEventDispatcher($dispatcher);
$response = $framework->handle($request);

$response->send();
