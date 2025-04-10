<?php declare(strict_types = 1);

namespace App\Infrastructure\Deserializer;

use App\Application\Dto\CreateUserDto;
use App\Domain\User\Role;
use App\Infrastructure\Exception\InvalidRequest;

class CreateUserRequestDeserializer
{

	public function fromRawJson(?string $rawBody): CreateUserDto
	{
		$data = json_decode($rawBody ?? '', true);

		if (!is_array($data)) {
			throw new InvalidRequest('Invalid JSON payload.');
		}

		foreach (['name', 'email', 'password', 'role'] as $key) {
			if (!array_key_exists($key, $data)) {
				throw new InvalidRequest(sprintf("Missing field '%s'.", $key));
			}
		}

		return new CreateUserDto(
			(string) $data['name'],
			(string) $data['email'],
			(string) $data['password'],
			Role::from((string) $data['role'])
		);
	}

}
