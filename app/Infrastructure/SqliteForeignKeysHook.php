<?php declare(strict_types = 1);

namespace App\Infrastructure;

use Nette\Database\Connection;

class SqliteForeignKeysHook
{

	public static function onConnect(Connection $connection): void
	{
		$connection->query('PRAGMA foreign_keys = ON');
	}

}
