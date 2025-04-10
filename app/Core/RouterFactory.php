<?php declare(strict_types = 1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;

final class RouterFactory
{

	use Nette\StaticClass;

	public static function createRouter(): Router
	{
		$router = new RouteList();
		$apiModule = $router->withModule('Api');
		$baseApiUrl = '';
		$apiModule->addRoute($baseApiUrl . '/', 'Intro:default');
		$apiModule->addRoute($baseApiUrl . '/articles[/<id>]', 'Article:default');
		$apiModule->addRoute($baseApiUrl . '/users[/<id>]', 'User:default');
		$apiModule->addRoute($baseApiUrl . '/auth/register', 'AuthRegister:default');
		$apiModule->addRoute($baseApiUrl . '/auth/login', 'AuthLogin:default');

		return $router;
	}

}
