<?php

namespace App\Tests;

use App\Framework;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class FrameworkTest extends TestCase
{

	public function testNotFoundHandling()
	{
		$framework = $this->getFrameworkForException(new ResourceNotFoundException());
		$response = $framework->handle(new Request());

		$this->assertEquals(404, $response->getStatusCode());
	}

	/**
	 * @param Exception $exception
	 * @return Framework
	 */
	private function getFrameworkForException(Exception $exception): Framework
	{
		$matcher = $this->createMock(UrlMatcherInterface::class);

		$matcher
			->expects($this->once())
			->method("match")
			->will($this->throwException($exception))
		;

		$matcher
			->expects($this->once())
			->method("getContext")
			->will($this->returnValue($this->createMock(RequestContext::class)))
		;

		// Pass an empty Route Collection, because we override the url Matcher anyway
		$framework = new Framework(new RouteCollection());

		// Override the Framework internal UrlMatcher with out mock one
		$framework->setUrlMatcher($matcher);

		return $framework;
	}

}
