<?php declare(strict_types = 1);

namespace App\Infrastructure\Database;

use App\Domain\AccessToken\AccessToken;
use App\Domain\AccessToken\AccessTokenRepository;
use App\Domain\User\Role;
use App\Domain\User\User;
use Nette\Database\Connection;

class AccessTokenDbRepository implements AccessTokenRepository
{

	public function __construct(
		readonly private Connection $connection
	)
	{
	}

	public function insert(AccessToken $accessToken): void
	{
		$this->connection->query(
			'INSERT INTO access_token ?',
			[
				'token' => $accessToken->token,
				'user_id' => $accessToken->user->id,
				'created_at' => $accessToken->createdAt->format('c'),
				'expires_at' => $accessToken->expiresAt->format('c'),
			]
		);
	}

	public function findByAccessToken(string $token): ?AccessToken
	{
		$row = $this->connection->fetch('
                SELECT 
                    access_token.token, access_token.created_at, access_token.expires_at,
                    user.id, user.role
                FROM access_token
                 INNER JOIN user on user.id = access_token.user_id
                         WHERE token = ?', $token);
		if (!$row) {
			return null;
		}

		return new AccessToken(
			$row->token,
			new User(
				$row->id,
				'',
				'',
				'',
				Role::from($row->role)
			),
			new \DateTimeImmutable((string) $row->created_at),
			new \DateTimeImmutable((string) $row->expires_at),
		);
	}

}
