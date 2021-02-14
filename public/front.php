<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Framework;
use App\Listener\ContentLengthListener;
use App\Listener\GoogleListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

$request = Request::createFromGlobals();
$routes = include __DIR__ . "/../src/app.php";

$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new ContentLengthListener());
$dispatcher->addSubscriber(new GoogleListener());

$framework = new Framework($routes);
$framework->setEventDispatcher($dispatcher);

$framework = new HttpCache(
	$framework,
	new Store(__DIR__."/../cache")
);

$response = $framework->handle($request);

$response->send();
