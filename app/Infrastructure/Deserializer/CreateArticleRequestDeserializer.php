<?php declare(strict_types = 1);

namespace App\Infrastructure\Deserializer;

use App\Application\Dto\CreateArticleDto;
use RuntimeException;

class CreateArticleRequestDeserializer
{

	public function fromRawJson(?string $rawBody, int $authorId): CreateArticleDto
	{
		$data = json_decode($rawBody ?? '', true);

		if (!is_array($data)) {
			throw new RuntimeException('Invalid JSON payload.');
		}

		foreach (['title', 'content'] as $key) {
			if (!array_key_exists($key, $data)) {
				throw new RuntimeException(sprintf("Missing field '%s'.", $key));
			}
		}

		return new CreateArticleDto(
			(string) $data['title'],
			(string) $data['content'],
			$authorId
		);
	}

}
