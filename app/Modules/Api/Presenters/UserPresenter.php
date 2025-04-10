<?php declare(strict_types = 1);

namespace App\Modules\Api\Presenters;

use App\Application\Facade\UserFacade;
use App\Domain\Exception\UnauthorizedException;
use App\Infrastructure\Deserializer\CreateUserRequestDeserializer;
use App\Infrastructure\Http\Response\JsonResponse;
use App\Infrastructure\Serializer\UserSerializer;

final class UserPresenter extends ProtectedApiPresenter
{

	public function __construct(
		private readonly UserFacade $userFacade,
		private readonly UserSerializer $userSerializer,
		private readonly CreateUserRequestDeserializer $createUserRequestDeserializer
	)
	{
	}

	public function actionDefault(?int $id): void
	{
		if ($id) {
			if ($this->getHttpRequest()->isMethod('DELETE')) {
				$this->deleteUser($id);
			}

			$this->userDetail($id);

		}

		if ($this->getHttpRequest()->isMethod('POST')) {
			$this->createUser();
		}

		$this->userList();
	}

	protected function startup(): void
	{
		parent::startup();

		if (!$this->isUserAdmin()) {
			throw new UnauthorizedException('You are not allowed to access this resource');
		}
	}

	private function userList(): JsonResponse
	{
		$this->sendResponse(new JsonResponse(
			$this->userSerializer->listToArray(
				$this->userFacade->getAll()
			)
		));
	}

	private function userDetail(int $id): JsonResponse
	{
		$user = $this->userFacade->getById($id);

		$this->sendResponse(new JsonResponse(
			$this->userSerializer->toArray($user)
		));
	}

	private function createUser(): JsonResponse
	{
		$createUserDto = $this->createUserRequestDeserializer->fromRawJson(
			$this->getHttpRequest()->getRawBody()
		);

		$this->userFacade->createUser($createUserDto);

		$this->sendResponse(
			new \Nette\Application\Responses\JsonResponse(
				[
					'status' => 'User created',
				]
			)
		);
	}

	private function deleteUser(int $id): void
	{
		$this->sendResponse(
			new JsonResponse(
				[
					'status' => 'TODO',
				]
			)
		);
	}

}
