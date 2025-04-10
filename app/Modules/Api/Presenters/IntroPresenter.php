<?php declare(strict_types = 1);

namespace App\Modules\Api\Presenters;

use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Presenter;

class IntroPresenter extends Presenter
{

	public function actionDefault(): void
	{
		$this->sendResponse(
			new JsonResponse(
				[
				'message' => 'Hello there',
				]
			)
		);
	}

}
