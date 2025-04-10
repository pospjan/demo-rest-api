<?php declare(strict_types = 1);

namespace App\Modules\Api\Presenters;

use App\Application\Security\BearerTokenAuthenticationService;
use App\Domain\User\Role;
use App\Domain\User\User;
use App\Infrastructure\Http\Response\JsonResponse;
use Nette\DI\Attributes\Inject;

class ProtectedApiPresenter extends ApiBasePresenter
{

	#[Inject]
	public BearerTokenAuthenticationService $bearerTokenAuthenticationService;

	protected ?User $user = null;

	protected function startup(): void
	{
		$this->user = $this->bearerTokenAuthenticationService->authenticate(
			$this->getHttpRequest()->getHeader('Authorization') ?? ''
		);

		if (in_array($this->getHttpRequest()->getMethod(), ['POST', 'PUT', 'DELETE'], true)) {
			if ($this->user === null) {
				$this->sendResponse(
					new JsonResponse(
						['error' => 'You are not allowed to access this resource'],
						403
					)
				);
			}
		}

		parent::startup();
	}

	protected function isUserAdmin(): bool
	{
		return $this->user !== null && $this->user->role === Role::Admin;
	}

}
