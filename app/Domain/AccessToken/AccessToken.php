<?php declare(strict_types = 1);

namespace App\Domain\AccessToken;

use App\Domain\User\User;

readonly class AccessToken
{

	public function __construct(
		public string $token,
		public User $user,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $expiresAt,
	)
	{
	}

	public static function createNew(string $token, User $user): self
	{
		return new self(
			$token,
			$user,
			new \DateTimeImmutable(),
			(new \DateTimeImmutable())->modify('+1 hour')
		);
	}

}
