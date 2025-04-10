<?php declare(strict_types = 1);

namespace App\Infrastructure\Database;

use App\Domain\Exception\EntityNotFoundException;
use App\Domain\User\Role;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Nette\Database\Connection;
use Nette\Database\Row;

final class UserDbRepository implements UserRepository
{

	public function __construct(readonly private Connection $connection)
	{
	}

	/**
	 * @return User[]
	 */
	public function getAll(): array
	{
		$users = $this->connection->query(
			'SELECT * FROM user'
		)->fetchAll();

		return array_map(
			fn ($user) => $this->mapUser($user),
			$users
		);
	}

	public function findByEmail(string $email): ?User
	{
		$user = $this->connection->query(
			'SELECT * FROM user WHERE email = ?',
			$email
		)->fetch();

		if (!$user) {
			return null;
		}

		return $this->mapUser($user);
	}

	public function insert(User $user): int
	{
		$this->connection->query(
			'INSERT INTO user ?',
			[
			'email' => $user->email,
			'name' => $user->name,
			'password_hash' => $user->passwordHash,
			'role' => $user->role->name,
			]
		);

		return (int) $this->connection->getInsertId();
	}

	public function findById(int $id): ?User
	{
		$user = $this->connection->query(
			'SELECT * FROM user WHERE id = ?',
			$id
		)->fetch();
		if (!$user) {
			return null;
		}

		return $this->mapUser($user);
	}

	public function getById(int $id): User
	{
		$user = $this->findById($id);
		if ($user === null) {
			throw new EntityNotFoundException('User not found');
		}

		return $user;
	}

	private function mapUser(Row $user): User
	{
		return new User(
			$user->id,
			$user->name,
			$user->email,
			$user->password_hash,
			Role::from($user->role),
		);
	}

}
