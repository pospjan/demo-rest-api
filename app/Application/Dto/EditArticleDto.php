<?php declare(strict_types = 1);

namespace App\Application\Dto;

readonly final class EditArticleDto
{

	public function __construct(
		public string $title,
		public string $content
	)
	{
	}

}
