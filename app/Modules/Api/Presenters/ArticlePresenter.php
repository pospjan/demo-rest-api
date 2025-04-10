<?php declare(strict_types = 1);

namespace App\Modules\Api\Presenters;

use App\Application\Facade\ArticleFacade;
use App\Infrastructure\Deserializer\CreateArticleRequestDeserializer;
use App\Infrastructure\Deserializer\EditArticleRequestDeserializer;
use App\Infrastructure\Exception\InvalidRequest;
use App\Infrastructure\Http\Response\JsonResponse;
use App\Infrastructure\Serializer\ArticleSerializer;
use Nette\Http\IResponse;

final class ArticlePresenter extends ProtectedApiPresenter
{

	public function __construct(
		private readonly ArticleSerializer $articleSerializer,
		private readonly CreateArticleRequestDeserializer $createArticleRequestDeserializer,
		private readonly EditArticleRequestDeserializer $editArticleRequestDeserializer,
		private readonly ArticleFacade $articleFacade
	)
	{
	}

	public function actionDefault(?int $id): void
	{
		if ($this->getHttpRequest()->isMethod('POST')) {
			$this->createArticle();
		}

		if ($this->getHttpRequest()->isMethod('DELETE')) {
			$this->deleteArticle($id);
		}

		if ($this->getHttpRequest()->isMethod('PUT')) {
			$this->editArticle($id);
		}

		if ($id) {
			$this->articleDetail($id);
		}

		$this->articleList();
	}

	private function articleList(): void
	{
		$articles = $this->articleFacade->getAll();
		$this->sendResponse(new JsonResponse(
			$this->articleSerializer->listToArray($articles)
		));
	}

	private function articleDetail(int $id): void
	{
		$article = $this->articleFacade->getById($id);

		$this->sendResponse(new JsonResponse(
			$this->articleSerializer->toArray($article)
		));
	}

	private function createArticle(): void
	{
		assert($this->user !== null);

		$createArticleDto = $this->createArticleRequestDeserializer->fromRawJson(
			$this->getHttpRequest()->getRawBody(),
			(int) $this->user->id
		);

		$this->articleFacade->createArticle($this->user, $createArticleDto);

		$this->sendResponse(
			new JsonResponse(
				[
					'status' => 'Article created',
				],
				IResponse::S201_Created
			)
		);
	}

	private function deleteArticle(?int $id): void
	{
		if (!$id) {
			throw new InvalidRequest('Article id not provided');
		}

		assert($this->user !== null);
		$this->articleFacade->deleteById($this->user, $id);

		$this->sendResponse(
			new JsonResponse(
				[
					'error' => 'Article deleted',
				],
				IResponse::S200_OK
			)
		);
	}

	private function editArticle(?int $id): void
	{
		if (!$id) {
			throw new InvalidRequest('Article id not provided');
		}

		$editArticleDto = $this->editArticleRequestDeserializer->fromRawJson(
			$this->getHttpRequest()->getRawBody()
		);

		assert($this->user !== null);
		$this->articleFacade->updateById($this->user, $id, $editArticleDto);

		$this->sendResponse(
			new JsonResponse(
				[
					'status' => 'Article updated',
				],
				IResponse::S200_OK
			)
		);
	}

}
