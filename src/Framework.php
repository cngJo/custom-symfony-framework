<?php

namespace App;

use App\Event\ResponseEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Framework implements HttpKernelInterface
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
	 * @var EventDispatcher
	 */
	private EventDispatcher $eventDispatcher;

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

		$this->eventDispatcher = new EventDispatcher();
	}

	/**
	 * Handle the given Request
	 *
	 * @param Request $request
	 * @param int $type
	 * @param bool $catch
	 * @return false|mixed|Response
	 */
	public function handle(
		Request $request,
		int $type = HttpKernelInterface::MASTER_REQUEST,
		bool $catch = true
	)
	{
		$this->urlMatcher->getContext()->fromRequest($request);

		try {
			$request->attributes->add($this->urlMatcher->match($request->getPathInfo()));

			$controller = $this->controllerResolver->getController($request);
			$arguments = $this->argumentResolver->getArguments($request, $controller);

			$response =  call_user_func_array($controller, $arguments);
		} catch (ResourceNotFoundException $exception){
			$response =  new Response("Not Found", 404);
		} catch (Exception $exception) {
			$response =  new Response("Internal Server Error", 500);
		}

		$this->eventDispatcher->dispatch(new ResponseEvent($response, $request), "response");

		return $response;
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

	/**
	 * @param EventDispatcher $eventDispatcher
	 */
	public function setEventDispatcher(EventDispatcher $eventDispatcher): void
	{
		$this->eventDispatcher = $eventDispatcher;
	}
}
