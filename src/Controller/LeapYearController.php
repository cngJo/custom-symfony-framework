<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{

	public function index(Request $request) : Response
	{
		if (is_leap_year($request->attributes->get("year"))) {
			return new Response("Yep. This is a leap year");
		}

		return new Response("Nope. This is not a leap year");
	}

}
