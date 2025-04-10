<?php declare(strict_types = 1);

namespace App\Modules\Api\Presenters;

use App\Application\Facade\UserFacade;
use App\Infrastructure\Deserializer\CreateUserRequestDeserializer;
use Nette\Application\Attributes\Requires;
use Nette\Application\Responses\JsonResponse;

final class AuthRegisterPresenter extends ApiBasePresenter
{

	public function __construct(
		private UserFacade $userFacade,
		private CreateUserRequestDeserializer $createUserRequestDeserializer
	)
	{
	}

	#[Requires(methods: ['POST'])]
	public function actionDefault(): void
	{
		$this->userFacade->createUser(
			$this->createUserRequestDeserializer->fromRawJson(
				$this->getHttpRequest()->getRawBody()
			)
		);

		$this->sendResponse(
			new JsonResponse(
				[
					'status' => 'User created',
				]
			)
		);
	}

}
