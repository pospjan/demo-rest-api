<?php declare(strict_types = 1);

namespace App\Infrastructure\Serializer;

use App\Domain\User\User;

class UserSerializer
{

	/**
	 * @return array<string, int|string>
	 */
	public function toArray(User $user): array
	{
		return [
			'id' => (int) $user->id,
			'name' => $user->name,
			'email' => $user->email,
			'role' => $user->role->value,
		];
	}

	/**
	 * @param User[] $users
	 * @return array<int, array<string, int|string>>
	 */
	public function listToArray(array $users): array
	{
		return array_map(fn (User $user) => $this->toArray($user), $users);
	}

}
