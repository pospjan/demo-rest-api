<?php declare(strict_types = 1);

namespace App\Domain\Article;

class Article
{

	public function __construct(
		private ?int $id,
		private string $title,
		private string $content,
		private int $authorId,
		private \DateTimeImmutable $createdAt,
		private \DateTimeImmutable $updatedAt,
	)
	{
	}

	public static function create(
		string $title,
		string $content,
		int $authorId,
	): self
	{
		return new self(
			null,
			$title,
			$content,
			$authorId,
			new \DateTimeImmutable(),
			new \DateTimeImmutable(),
		);
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}

	public function edit(
		string $title,
		string $content
	): void
	{
		$this->title = $title;
		$this->content = $content;
		$this->updatedAt = new \DateTimeImmutable();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function getAuthorId(): int
	{
		return $this->authorId;
	}

	public function getCreatedAt(): \DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): \DateTimeImmutable
	{
		return $this->updatedAt;
	}

}
