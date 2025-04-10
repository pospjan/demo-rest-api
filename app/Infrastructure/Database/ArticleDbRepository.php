<?php declare(strict_types = 1);

namespace App\Infrastructure\Database;

use App\Domain\Article\Article;
use App\Domain\Article\ArticleRepository;
use App\Domain\Exception\EntityNotFoundException;
use Nette\Database\Connection;
use Nette\Database\Row;

class ArticleDbRepository implements ArticleRepository
{

	public function __construct(
		private Connection $connection,
	)
	{
	}

	public function insert(Article $article): int
	{
		$this->connection->query(
			'INSERT INTO article ?',
			[
			'title' => $article->getTitle(),
			'content' => $article->getContent(),
			'author_id' => $article->getAuthorId(),
			'created_at' => $article->getCreatedAt()->format('c'),
			'updated_at' => $article->getUpdatedAt()->format('c'),
			]
		);

		return (int) $this->connection->getInsertId();
	}

	/**
	 * @return Article[]
	 */
	public function getAll(): array
	{
		$articles = $this->connection->query('SELECT * FROM article')->fetchAll();

		return array_map(
			fn (Row $article) => $this->mapArticle($article),
			$articles
		);
	}

	public function findById(int $id): ?Article
	{
		$article = $this->connection->query(
			'SELECT * FROM article WHERE id = ?',
			$id
		)->fetch();

		if (!$article) {
			return null;
		}

		return $this->mapArticle($article);
	}

	public function getById(int $id): Article
	{
		$article = $this->findById($id);

		if ($article === null) {
			throw new EntityNotFoundException('Article not found');
		}

		return $article;
	}

	public function deleteById(int $id): void
	{
		$this->connection->query(
			'DELETE FROM article WHERE id = ?',
			$id
		);
	}

	public function update(Article $article): void
	{
		$this->connection->query(
			'UPDATE article SET title = ?, content = ?, updated_at = ? WHERE id = ?',
			$article->getTitle(),
			$article->getContent(),
			$article->getUpdatedAt()->format('c'),
			$article->getId()
		);
	}

	private function mapArticle(Row $article): Article
	{
		return new Article(
			$article->id,
			$article->title,
			$article->content,
			$article->author_id,
			new \DateTimeImmutable((string) $article->created_at),
			new \DateTimeImmutable((string) $article->updated_at),
		);
	}

}
