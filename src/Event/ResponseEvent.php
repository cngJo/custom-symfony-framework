<?php

namespace App\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseEvent extends Event
{

	public function __construct(
		private Response $response,
		private Request $request
	) { /* */ }

	/**
	 * @return Response
	 */
	public function getResponse(): Response
	{
		return $this->response;
	}

	/**
	 * @return Request
	 */
	public function getRequest(): Request
	{
		return $this->request;
	}
}
