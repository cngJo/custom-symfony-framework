<?php

namespace App\Models;

use JetBrains\PhpStorm\Pure;

class LeapYear
{

	/**
	 * Returns true, if the given year is a leap year
	 *
	 * @param string|null $year
	 * @return bool
	 */
	#[Pure] public function isLeapYear(?string $year = null) : bool
	{
		if (null === $year) {
			$year = date('Y');
		}

		return 0 == $year % 400 || (0 == $year % 4 && 0 != $year % 100);
	}

}
