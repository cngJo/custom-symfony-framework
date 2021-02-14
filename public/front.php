<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Framework;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$requestStack = new RequestStack();
$routes = include __DIR__.'/../src/app.php';

$context = new Routing\RequestContext();
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$controllerResolver = new HttpKernel\Controller\ControllerResolver();
$argumentResolver = new HttpKernel\Controller\ArgumentResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, $requestStack));
$dispatcher->addSubscriber(new ErrorListener("App\Controller\ErrorController::exception"));

$framework = new Framework($dispatcher, $controllerResolver, $requestStack, $argumentResolver);

$response = $framework->handle($request);
$response->send();
