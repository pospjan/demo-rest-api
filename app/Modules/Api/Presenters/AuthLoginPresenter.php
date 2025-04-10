<?php declare(strict_types = 1);

namespace App\Modules\Api\Presenters;

use App\Application\Dto\LoginUserDto;
use App\Application\Facade\LoginFacade;
use App\Infrastructure\Exception\InvalidRequest;
use App\Infrastructure\Http\Response\JsonResponse;
use Nette\Application\Attributes\Requires;

final class AuthLoginPresenter extends ApiBasePresenter
{

	public function __construct(
		private LoginFacade $loginFacade
	)
	{
	}

	#[Requires(methods: ['POST'])]
	public function actionDefault(): void
	{
		$rawBody = $this->getHttpRequest()->getRawBody();
		$data = json_decode($rawBody ?? '', true);

		if (!is_array($data)) {
			throw new InvalidRequest('Invalid JSON payload.');
		}

		$accessToken = $this->loginFacade->loginUser(
			new LoginUserDto(
				$data['email'],
				$data['password'],
			)
		);

		$this->sendResponse(
			new JsonResponse(
				[
					'accessToken' => $accessToken->token,
					'expiresAt' => $accessToken->expiresAt->format('c'),
				]
			)
		);
	}

}
