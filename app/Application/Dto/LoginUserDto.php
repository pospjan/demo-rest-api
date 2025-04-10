<?php declare(strict_types = 1);

namespace App\Application\Dto;

readonly final class LoginUserDto
{

	public function __construct(
		public string $email,
		public string $password
	)
	{
	}

}
