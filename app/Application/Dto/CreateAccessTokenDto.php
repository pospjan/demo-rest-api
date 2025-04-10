<?php declare(strict_types = 1);

namespace App\Application\Dto;

readonly final class CreateAccessTokenDto
{

	public function __construct(
		public string $token,
		public int $userId,
		public \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
		public \DateTimeImmutable $expiresAt = new \DateTimeImmutable('+24 hour')
	)
	{
	}

}
