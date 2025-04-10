<?php declare(strict_types = 1);

namespace App\Domain\User;

interface UserRepository
{

	/**
	 * @return User[]
	 */
	public function getAll(): array;

	public function findById(int $id): ?User;

	public function getById(int $id): User;

	public function findByEmail(string $email): ?User;

	public function insert(User $user): int;

}
