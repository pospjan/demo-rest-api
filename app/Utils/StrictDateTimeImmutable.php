<?php declare(strict_types = 1);

namespace App\Utils;

use DateTimeImmutable;
use DateTimeZone;

class StrictDateTimeImmutable extends \DateTimeImmutable
{

	public static function createFromFormat(string $format, string $datetime, DateTimeZone|null $timezone = null): DateTimeImmutable
	{
		$dateTimeImmutable = parent::createFromFormat($format, $datetime, $timezone);
		if ($dateTimeImmutable === false) {
			throw new \InvalidArgumentException('Invalid date format');
		}

		return $dateTimeImmutable;
	}

}
