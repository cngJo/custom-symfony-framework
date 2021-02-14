<?php


namespace App\Controller;


use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ByeController
{
	/**
	 * @param Request $request
	 * @return Response
	 * @throws \Exception
	 */
	public function byeAction(Request $request): Response
	{
		throw new Exception("Test");

		return new Response("Goodbye!");
	}
}
