<?php declare(strict_types = 1);

namespace App\Application\Facade;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\AccessTokenRepository;
use App\Domain\User\User;
use Nette\Utils\Random;

class AccessTokenFacade
{

	public function __construct(
		readonly private AccessTokenRepository $accessTokenRepository
	)
	{
	}

	public function createAccessTokenForUser(User $user): AccessToken
	{
		return $this->insertAccessTokenForUser(
			Random::generate(32),
			$user
		);
	}

	public function insertAccessTokenForUser(string $token, User $user): AccessToken
	{
		$accessToken = AccessToken::createNew(
			$token,
			$user
		);

		$this->accessTokenRepository->insert(
			$accessToken
		);

		return $accessToken;
	}

}
