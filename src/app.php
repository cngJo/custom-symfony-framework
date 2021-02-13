<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

use App\Controller\LeapYearController;

function is_leap_year($year = null) {
	if (null === $year) {
		$year = date("Y");
	}

	return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
}

$routes = new RouteCollection();

$routes->add('hello', new Route('/hello/{name}', [
	'name' => 'World',
	"_controller" => function (Request $request) {
		$request->attributes->set("foo", "bar");
		return render_template($request);
	}
]));
$routes->add('bye', new Route('/bye'));

$routes->add("leap_year", new Route("/is_leap_year/{year}", [
	"year" => null,
	"_controller" => [new LeapYearController(), "index"]
]));

return $routes;
