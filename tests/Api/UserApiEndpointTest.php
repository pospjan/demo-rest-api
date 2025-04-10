<?php declare(strict_types = 1);

namespace Tests\Api;

use App\Infrastructure\Http\Response\JsonResponse;
use App\Utils\SqlFileImporter;
use Nette;
use Nette\DI\Container;
use Tester\Assert;
use Tests\Utils\PresenterTestHelper;

/** @var Container $container */
$container = require_once __DIR__ . '/../Bootstrap.php';

setUp(function () use ($container): void {
	$sqlImporter = $container->getByType(SqlFileImporter::class);

	$sqlImporter->import(__DIR__ . '/../../sql/schema.sql');
	$sqlImporter->import(__DIR__ . '/../Fixtures/data.sql');
});


test('User list isn\'t accessible to anonymous users', function () use ($container): void {
	$request = new Nette\Application\Request(
		'Api:User',
		'GET'
	);

	$factory = $container->getByType(Nette\Application\IPresenterFactory::class);
	$presenter = $factory->createPresenter('Api:User');

	$response = $presenter->run($request);
	Assert::true($response instanceof JsonResponse);

	Assert::same($response->getResponseCode(), 403);
	Assert::same(['error' => 'You are not allowed to access this resource'], $response->getPayload());
});


test('User list isn\'t accessible to user with a author role', function () use ($container): void {
	$presenter = PresenterTestHelper::createUserPresenterWithHttpRequest(
		$container,
		'localhost/users',
		'GET',
		[],
		[
			'Authorization' => 'Bearer ghi789',
		]
	);

	$request = new Nette\Application\Request(
		'Api:User',
		'GET',
		['action' => 'default']
	);

	$response = $presenter->run($request);
	Assert::true($response instanceof JsonResponse);

	Assert::same($response->getResponseCode(), 403);
	Assert::same(['error' => 'You are not allowed to access this resource'], $response->getPayload());
});

test('Admin can access the user list', function () use ($container): void {
	$presenter = PresenterTestHelper::createUserPresenterWithHttpRequest(
		$container,
		'localhost/users',
		'GET',
		[],
		[
			'Authorization' => 'Bearer abc123',
		]
	);

	$request = new Nette\Application\Request(
		'Api:User',
		'GET',
		['action' => 'default']
	);

	$response = $presenter->run($request);
	Assert::true($response instanceof JsonResponse);

	Assert::same($response->getResponseCode(), 200);
	Assert::count(3, $response->getPayload());
});
