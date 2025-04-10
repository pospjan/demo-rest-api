<?php declare(strict_types = 1);

namespace App\Application\Security;

use App\Domain\AccessToken\AccessTokenRepository;
use App\Domain\User\User;
use App\Domain\User\UserRepository;

class BearerTokenAuthenticationService
{

	public function __construct(
		private readonly AccessTokenRepository $accessTokenRepository,
		private readonly UserRepository $userRepository,
	)
	{
	}

	public function authenticate(string $token): ?User
	{
		if (str_starts_with($token, 'Bearer ')) {
			$token = substr($token, 7);
		}

		$tokenEntity = $this->accessTokenRepository->findByAccessToken($token);

		if ($tokenEntity === null) {
			return null;
		}

		$user = $this->userRepository->findById(
			(int) $tokenEntity->user->id
		);
		if ($user === null) {
			return null;
		}

		return $user;
	}

}
