<?php declare(strict_types = 1);

namespace Tests\Utils;

use App\Application\Facade\ArticleFacade;
use App\Application\Facade\UserFacade;
use App\Application\Security\BearerTokenAuthenticationService;
use App\Infrastructure\Deserializer\CreateArticleRequestDeserializer;
use App\Infrastructure\Deserializer\CreateUserRequestDeserializer;
use App\Infrastructure\Deserializer\EditArticleRequestDeserializer;
use App\Infrastructure\Serializer\ArticleSerializer;
use App\Infrastructure\Serializer\UserSerializer;
use App\Modules\Api\Presenters\ArticlePresenter;
use App\Modules\Api\Presenters\UserPresenter;
use Nette\Application\IPresenterFactory;
use Nette\Application\UI\Presenter;
use Nette\DI\Container;
use Nette\Http\Request;
use Nette\Http\Response;
use Nette\Http\UrlScript;
use Nette\Routing\Router;
use Nette\Security\User;

class PresenterTestHelper
{

	/**
	 * @param mixed[] $postData
	 * @param mixed[] $headers
	 */
	public static function createArticlePresenterWithHttpRequest(
		Container $container,
		string $url,
		string $method = 'GET',
		array $postData = [],
		array $headers = [],
		?callable $rawBodyCallback = null
	): Presenter
	{
		$url = new UrlScript($url);

		$httpRequest = new Request(
			$url,
			$postData,
			[],
			[],
			$headers,
			$method,
			null,
			null,
			$rawBodyCallback,
		);

		$presenter = new ArticlePresenter(
			$container->getByType(ArticleSerializer::class),
			$container->getByType(CreateArticleRequestDeserializer::class),
			$container->getByType(EditArticleRequestDeserializer::class),
			$container->getByType(ArticleFacade::class)
		);
		$presenter->bearerTokenAuthenticationService = $container->getByType(BearerTokenAuthenticationService::class);

		$presenter->injectPrimary(
			$httpRequest,
			$container->getByType(Response::class),
			$container->getByType(IPresenterFactory::class),
			$container->getByType(Router::class),
			null,
			$container->getByType(User::class),
			null
		);

		return $presenter;
	}

	/**
	 * @param mixed[] $postData
	 * @param mixed[] $headers
	 */
	public static function createUserPresenterWithHttpRequest(
		Container $container,
		string $url,
		string $method = 'GET',
		array $postData = [],
		array $headers = [],
		?callable $rawBodyCallback = null
	): Presenter
	{
		$url = new UrlScript($url);

		$httpRequest = new Request(
			$url,
			$postData,
			[],
			[],
			$headers,
			$method,
			null,
			null,
			$rawBodyCallback,
		);

		$presenter = new UserPresenter(
			$container->getByType(UserFacade::class),
			$container->getByType(UserSerializer::class),
			$container->getByType(CreateUserRequestDeserializer::class)
		);

		$presenter->bearerTokenAuthenticationService = $container->getByType(BearerTokenAuthenticationService::class);

		$presenter->injectPrimary(
			$httpRequest,
			$container->getByType(Response::class),
			$container->getByType(IPresenterFactory::class),
			$container->getByType(Router::class),
			null,
			$container->getByType(User::class),
			null
		);

		return $presenter;
	}

}
