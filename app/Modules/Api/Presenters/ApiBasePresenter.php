<?php declare(strict_types = 1);

namespace App\Modules\Api\Presenters;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\UnauthorizedException;
use App\Infrastructure\Http\Response\JsonResponse;
use Nette\Application\BadRequestException;
use Nette\Application\Request;
use Nette\Application\Response;
use Nette\Application\UI\Presenter;
use Nette\Http\IResponse;

class ApiBasePresenter extends Presenter
{

	public function run(Request $request): Response
	{
		try {
			return parent::run($request);
		} catch (\Throwable $e) {
			return $this->handleException($e);
		}
	}

	public function handleException(\Throwable $e): Response
	{
		$code = IResponse::S400_BadRequest;
		$error = $e->getMessage();

		if ($e instanceof EntityNotFoundException) {
			$code = IResponse::S404_NotFound;
		}

		if ($e instanceof UnauthorizedException) {
			$code = IResponse::S403_Forbidden;
		}

		if ($e instanceof BadRequestException) {
			$error = 'Bad request';
		}

		return new JsonResponse(
			['error' => $error],
			$code
		);
	}

}
