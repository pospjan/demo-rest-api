<?php declare(strict_types = 1);

namespace App\Domain\User;

readonly class User
{

	public function __construct(
		public ?int $id,
		public string $name,
		public string $email,
		public string $passwordHash,
		public Role $role
	)
	{
	}

	public function cloneWithId(int $id): self
	{
		return new self(
			$id,
			$this->name,
			$this->email,
			$this->passwordHash,
			$this->role
		);
	}

}
