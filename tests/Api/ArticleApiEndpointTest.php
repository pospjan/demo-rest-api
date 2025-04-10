<?php declare(strict_types = 1);

namespace Tests\Api;

use App\Infrastructure\Http\Response\JsonResponse;
use App\Utils\SqlFileImporter;
use Nette;
use Nette\DI\Container;
use Tester\Assert;
use Tests\Utils\PresenterTestHelper;
use function assert;

/** @var Container $container */
$container = require_once __DIR__ . '/../Bootstrap.php';

setUp(function () use ($container): void {
	$sqlImporter = $container->getByType(SqlFileImporter::class);

	$sqlImporter->import(__DIR__ . '/../../sql/schema.sql');
	$sqlImporter->import(__DIR__ . '/../Fixtures/data.sql');
});


test('Article list', function () use ($container): void {
	$request = new Nette\Application\Request(
		'Api:Article',
		'GET'
	);

	$factory = $container->getByType(Nette\Application\IPresenterFactory::class);
	$presenter = $factory->createPresenter('Api:Article');

	$response = $presenter->run($request);
	Assert::true($response instanceof JsonResponse);

	Assert::same($response->getResponseCode(), 200);

	$payload = $response->getPayload();
	Assert::type('array', $payload);
	Assert::count(2, $payload);

	Assert::same([
		'id' => 2,
		'title' => 'Titulek 2',
		'content' => 'Obsah 2',
		'createdAt' => '2024-04-02T10:00:00+00:00',
		'updatedAt' => '2024-04-02T12:00:00+00:00',
	], $payload[1]);
});


test('User with invalid token can not create article', function () use ($container): void {
	$presenter = PresenterTestHelper::createArticlePresenterWithHttpRequest(
		$container,
		'localhost/articles',
		'POST',
		[],
		[
			'Authorization' => 'Bearer invalid_token',
		],
		fn () => json_encode([
				'title' => 'Text title',
				'content' => 'Test content',
			])
	);

	$request = new Nette\Application\Request(
		'Api:Article',
		'POST',
		['action' => 'default']
	);

	$connection = $container->getByType(Nette\Database\Connection::class);

	$articlesBefore = $connection->query(
		'SELECT count(*) as sum FROM article'
	)->fetch();

	assert($articlesBefore !== null);

	Assert::same(2, $articlesBefore->sum);

	$response = $presenter->run($request);
	Assert::true($response instanceof JsonResponse);

	Assert::same(['error' => 'You are not allowed to access this resource'], $response->getPayload());
	Assert::same(403, $response->getResponseCode());

	$articlesAfter = $connection->query(
		'SELECT count(*) as sum FROM article'
	)->fetch();

	assert($articlesAfter !== null);

	Assert::same(2, $articlesAfter->sum);
});


test('Reader can not create article', function () use ($container): void {
	$presenter = PresenterTestHelper::createArticlePresenterWithHttpRequest(
		$container,
		'localhost/articles',
		'POST',
		[],
		[
			'Authorization' => 'Bearer token_reader',
		],
		fn () => json_encode([
				'title' => 'Text title',
				'content' => 'Test content',
			])
	);

	$request = new Nette\Application\Request(
		'Api:Article',
		'POST',
		['action' => 'default']
	);

	$connection = $container->getByType(Nette\Database\Connection::class);

	$articlesBefore = $connection->query(
		'SELECT count(*) as sum FROM article'
	)->fetch();

	assert($articlesBefore !== null);

	Assert::same(2, $articlesBefore->sum);

	$response = $presenter->run($request);
	Assert::true($response instanceof JsonResponse);

	Assert::same(['error' => 'You are not allowed to delete this article'], $response->getPayload());
	Assert::same(403, $response->getResponseCode());

	$articlesAfter = $connection->query(
		'SELECT count(*) as sum FROM article'
	)->fetch();

	assert($articlesAfter !== null);

	Assert::same(2, $articlesAfter->sum);
});

test('Author can create article', function () use ($container): void {
	$presenter = PresenterTestHelper::createArticlePresenterWithHttpRequest(
		$container,
		'localhost/articles',
		'POST',
		[],
		[
			'Authorization' => 'Bearer ghi789',
		],
		fn () => json_encode([
				'title' => 'Text title',
				'content' => 'Test content',
			])
	);

	$request = new Nette\Application\Request(
		'Api:Article',
		'POST',
		['action' => 'default']
	);

	$connection = $container->getByType(Nette\Database\Connection::class);

	$articlesBefore = $connection->query(
		'SELECT count(*) as sum FROM article'
	)->fetch();

	assert($articlesBefore !== null);

	Assert::same(2, $articlesBefore->sum);

	$response = $presenter->run($request);
	Assert::true($response instanceof JsonResponse);

	Assert::same(['status' => 'Article created'], $response->getPayload());
	Assert::same(201, $response->getResponseCode());

	$articlesAfter = $connection->query(
		'SELECT count(*) as sum FROM article'
	)->fetch();

	assert($articlesAfter !== null);

	Assert::same(3, $articlesAfter->sum);
});
