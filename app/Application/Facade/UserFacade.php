<?php declare(strict_types = 1);

namespace App\Application\Facade;

use App\Application\Dto\CreateUserDto;
use App\Application\Dto\LoginUserDto;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Exception\UserWithEmailAlreadyExistsException;
use App\Domain\User\User;
use App\Domain\User\UserRepository;
use Nette\Security\Passwords;

class UserFacade
{

	public function __construct(
		readonly private UserRepository $userRepository,
		readonly private Passwords $passwords
	)
	{
	}

	public function createUser(CreateUserDto $dto): User
	{
		if ($this->userRepository->findByEmail($dto->email)) {
			throw new UserWithEmailAlreadyExistsException('User with this email already exists');
		}

		$user = new User(
			null,
			$dto->name,
			$dto->email,
			$this->passwords->hash($dto->password),
			$dto->role,
		);

		$id = $this->userRepository->insert($user);

		return $user->cloneWithId($id);
	}

	public function verifyUser(LoginUserDto $dto): User
	{
		$user = $this->userRepository->findByEmail($dto->email);

		if (!$user) {
			throw new EntityNotFoundException('User not found');
		}

		if (!$this->passwords->verify($dto->password, $user->passwordHash)) {
			throw new \DomainException('Invalid password');
		}

		return $user;
	}

	/**
	 * @return User[]
	 */
	public function getAll(): array
	{
		return $this->userRepository->getAll();
	}

	public function findById(int $id): ?User
	{
		return $this->userRepository->findById($id);
	}

	public function getById(int $id): User
	{
		return $this->userRepository->getById($id);
	}

}
