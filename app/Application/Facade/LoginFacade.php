<?php declare(strict_types = 1);

namespace App\Application\Facade;

use App\Application\Dto\LoginUserDto;
use App\Domain\AccessToken\AccessToken;

class LoginFacade
{

	public function __construct(
		readonly private UserFacade $userFacade,
		readonly private AccessTokenFacade $accessTokenFacade
	)
	{
	}

	public function loginUser(LoginUserDto $loginUserDto): AccessToken
	{
		$user = $this->userFacade->verifyUser($loginUserDto);

		return $this->accessTokenFacade->createAccessTokenForUser($user);
	}

}
