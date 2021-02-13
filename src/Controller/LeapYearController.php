<?php

namespace App\Controller;

use App\Models\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{

	public function index(Request $request, $year) : Response
	{
		if ((new LeapYear())->isLeapYear($year)) {
			return new Response("Yep. This is a leap year");
		}

		return new Response("Nope. This is not a leap year");
	}

}
