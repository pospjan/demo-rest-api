<?php declare(strict_types = 1);

use App\Domain\Article\Article;
use App\Infrastructure\Serializer\ArticleSerializer;
use Tester\Assert;

require_once __DIR__ . '/../Bootstrap.php';

test('Serialize of empty array should produce empty array', function (): void {
	$serializer = new ArticleSerializer();
	$result = $serializer->listToArray([]);
	Assert::same([], $result);
});


test('Serialize of Articles works as expected', function (): void {
	$serializer = new ArticleSerializer();
	$result = $serializer->listToArray([
		new Article(
			1,
			'Test Title',
			'Test Content',
			2,
			new DateTimeImmutable('2024-04-02T10:00:00+00:00'),
			new DateTimeImmutable('2024-04-02T12:00:00+00:00')
		),
		new Article(
			2,
			'Another Title',
			'Another Content',
			1,
			new DateTimeImmutable('2024-04-03T10:00:00+00:00'),
			new DateTimeImmutable('2024-04-03T12:00:00+00:00')
		),
	]);

	Assert::same([
		[
			'id' => 1,
			'title' => 'Test Title',
			'content' => 'Test Content',
			'createdAt' => '2024-04-02T10:00:00+00:00',
			'updatedAt' => '2024-04-02T12:00:00+00:00',
		],
		[
			'id' => 2,
			'title' => 'Another Title',
			'content' => 'Another Content',
			'createdAt' => '2024-04-03T10:00:00+00:00',
			'updatedAt' => '2024-04-03T12:00:00+00:00',
		],
	], $result);
});
