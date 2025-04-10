<?php declare(strict_types = 1);

namespace App\Application\Dto;

use App\Domain\User\Role;

readonly final class CreateUserDto
{

	public function __construct(
		public string $name,
		public string $email,
		public string $password,
		public Role $role
	)
	{
	}

}
