<?php declare(strict_types = 1);

namespace App\Domain\Article;

interface ArticleRepository
{

	public function insert(Article $article): int;

	public function update(Article $article): void;

	/**
	 * @return Article[]
	 */
	public function getAll(): array;

	public function findById(int $id): ?Article;

	public function getById(int $id): Article;

	public function deleteById(int $id): void;

}
