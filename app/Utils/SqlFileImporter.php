<?php declare(strict_types = 1);

namespace App\Utils;

use Nette\Database\Connection;

class SqlFileImporter
{

	public function __construct(private readonly Connection $connection)
	{
	}

	public function import(string $filePath): void
	{
		if (!file_exists($filePath)) {
			throw new \RuntimeException('File not found');
		}

		$sqlContent = file_get_contents($filePath);

		if ($sqlContent === false) {
			throw new \RuntimeException('Failed to read file');
		}

		$queries = array_filter(array_map('trim', explode(';', $sqlContent)));

		foreach ($queries as $query) {
			// @phpstan-ignore notIdentical.alwaysTrue
			if ($query !== '') {
				// @phpstan-ignore argument.type
				$this->connection->query($query);
			}
		}
	}

}
