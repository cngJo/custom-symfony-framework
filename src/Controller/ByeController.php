<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ByeController
{
	/**
	 * @param Request $request
	 * @return Response
	 */
	public function byeAction(Request $request): Response
	{
		return new Response("Goodbye!");
	}
}
