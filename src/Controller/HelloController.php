<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelloController
{

	/**
	 * @param Request $request
	 * @param string $name
	 * @return string
	 */
	public function helloAction(Request $request, string $name = "World"): string
	{
		return "Hello, $name";
	}
}
