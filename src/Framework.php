<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;

class Framework
{
	/**
	 * Framework constructor.
	 *
	 * @param UrlMatcher $urlMatcher
	 * @param ControllerResolver $controllerResolver
	 * @param ArgumentResolver $argumentResolver
	 */
	public function __construct(
		private UrlMatcher $urlMatcher,
		private ControllerResolver $controllerResolver,
		private ArgumentResolver $argumentResolver
	)
	{
		// Silence is golden
	}

	/**
	 * Handle the given Request
	 *
	 * @param Request $request
	 * @return false|mixed|Response
	 */
	public function handle(Request $request)
	{
		$this->urlMatcher->getContext()->fromRequest($request);

		try {
			$request->attributes->add($this->urlMatcher->match($request->getPathInfo()));

			$controller = $this->controllerResolver->getController($request);
			$arguments = $this->argumentResolver->getArguments($request, $controller);

			return call_user_func_array($controller, $arguments);
		} catch (ResourceNotFoundException $exception){
			return new Response("Not Found", 404);
		} catch (Exception $exception) {
			return new Response("Internal Server Error", 500);
		}
	}

}
