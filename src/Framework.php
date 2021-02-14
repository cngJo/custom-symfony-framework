<?php

namespace App;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Framework
{
	/**
	 * @var UrlMatcherInterface
	 */
	private UrlMatcherInterface $urlMatcher;

	/**
	 * @var ControllerResolver
	 */
	private ControllerResolver $controllerResolver;

	/**
	 * @var ArgumentResolver
	 */
	private ArgumentResolver $argumentResolver;

	/**
	 * Framework constructor.
	 *
	 * @param RouteCollection $routes
	 */
	public function __construct(RouteCollection $routes)
	{
		$this->urlMatcher = new UrlMatcher($routes, new RequestContext());

		$this->controllerResolver = new ControllerResolver();
		$this->argumentResolver = new ArgumentResolver();
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

	/// Provide setters to change the default instantiated classes

	/**
	 * @param UrlMatcherInterface $urlMatcher
	 */
	public function setUrlMatcher(UrlMatcherInterface $urlMatcher): void
	{
		$this->urlMatcher = $urlMatcher;
	}

	/**
	 * @param ControllerResolver $controllerResolver
	 */
	public function setControllerResolver(ControllerResolver $controllerResolver): void
	{
		$this->controllerResolver = $controllerResolver;
	}

	/**
	 * @param ArgumentResolver $argumentResolver
	 */
	public function setArgumentResolver(ArgumentResolver $argumentResolver): void
	{
		$this->argumentResolver = $argumentResolver;
	}

}
