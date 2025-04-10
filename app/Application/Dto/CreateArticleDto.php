<?php declare(strict_types = 1);

namespace App\Application\Dto;

readonly final class CreateArticleDto
{

	public function __construct(
		public string $title,
		public string $content,
		public int $authorId,
	)
	{
	}

}
