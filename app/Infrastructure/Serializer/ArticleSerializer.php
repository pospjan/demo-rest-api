<?php declare(strict_types = 1);

namespace App\Infrastructure\Serializer;

use App\Domain\Article\Article;

class ArticleSerializer
{

	/**
	 * @return array<string, int|string>
	 */
	public function toArray(Article $article): array
	{
		return [
			'id' => (int) $article->getId(),
			'title' => $article->getTitle(),
			'content' => $article->getContent(),
			'createdAt' => $article->getCreatedAt()->format('c'),
			'updatedAt' => $article->getUpdatedAt()->format('c'),
		];
	}

	/**
	 * @param Article[] $articles
	 * @return array<int, array<string, int|string>>
	 */
	public function listToArray(array $articles): array
	{
		return array_map(fn (Article $article) => $this->toArray($article), $articles);
	}

}
