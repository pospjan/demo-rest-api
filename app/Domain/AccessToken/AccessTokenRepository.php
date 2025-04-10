<?php declare(strict_types = 1);

namespace App\Domain\AccessToken;

interface AccessTokenRepository
{

	public function insert(AccessToken $accessToken): void;

	public function findByAccessToken(string $token): ?AccessToken;

}
