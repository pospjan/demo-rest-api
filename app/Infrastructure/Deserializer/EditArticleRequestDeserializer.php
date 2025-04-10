<?php declare(strict_types = 1);

namespace App\Infrastructure\Deserializer;

use App\Application\Dto\EditArticleDto;
use RuntimeException;

class EditArticleRequestDeserializer
{

	public function fromRawJson(?string $rawBody): EditArticleDto
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

		return new EditArticleDto(
			(string) $data['title'],
			(string) $data['content']
		);
	}

}
