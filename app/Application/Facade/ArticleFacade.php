<?php declare(strict_types = 1);

namespace App\Application\Facade;

use App\Application\Dto\CreateArticleDto;
use App\Application\Dto\EditArticleDto;
use App\Domain\Article\Article;
use App\Domain\Article\ArticlePolicy;
use App\Domain\Article\ArticleRepository;
use App\Domain\Exception\UnauthorizedException;
use App\Domain\User\User;

class ArticleFacade
{

	public function __construct(
		readonly private ArticleRepository $articleRepository,
		readonly private ArticlePolicy $articlePolicy,
	)
	{
	}

	public function createArticle(User $user, CreateArticleDto $articleDto): Article
	{
		if (!$this->articlePolicy->canCreate($user)) {
			throw new UnauthorizedException('You are not allowed to delete this article');
		}

		$article = Article::create(
			$articleDto->title,
			$articleDto->content,
			$articleDto->authorId
		);

		$id = $this->articleRepository->insert($article);

		$article->setId($id);

		return $article;
	}

	/**
	 * @return Article[]
	 */
	public function getAll(): array
	{
		return $this->articleRepository->getAll();
	}

	public function findById(int $id): ?Article
	{
		return $this->articleRepository->findById($id);
	}

	public function getById(int $id): Article
	{
		return $this->articleRepository->getById($id);
	}

	public function deleteById(User $user, int $id): void
	{
		$article = $this->articleRepository->getById($id);

		if (!$this->articlePolicy->canDelete($user, $article)) {
			throw new UnauthorizedException('You are not allowed to delete this article');
		}

		$this->articleRepository->deleteById($id);
	}

	public function updateById(User $user, int $id, EditArticleDto $articleDto): void
	{
		$article = $this->articleRepository->getById($id);

		if (!$this->articlePolicy->canEdit($user, $article)) {
			throw new UnauthorizedException('You are not allowed to update this article');
		}

		$article->edit(
			$articleDto->title,
			$articleDto->content
		);

		$this->articleRepository->update($article);
	}

}
