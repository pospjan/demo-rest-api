<?php declare(strict_types = 1);

// @phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

namespace Tests;

use Nette\Bootstrap\Configurator;
use Nette\DI\Container;
use Tester\Environment;

require __DIR__ . '/../vendor/autoload.php';

Environment::setup();
Environment::setupFunctions();

class Bootstrap
{

	private Configurator $configurator;

	private string $rootDir;

	public function __construct()
	{
		$this->rootDir = dirname(__DIR__);
		$this->configurator = new Configurator();
		$this->configurator->setTempDirectory($this->rootDir . '/temp');
	}

	public function bootTests(): Container
	{
		$this->initializeEnvironment();
		$this->setupContainer();

		return $this->configurator->createContainer();
	}

	public function initializeEnvironment(): void
	{
		$this->configurator->enableTracy($this->rootDir . '/log');

		$this->configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->addDirectory(__DIR__ . '/../app')
			->register();
	}

	private function setupContainer(): void
	{
		$configDir = $this->rootDir . '/config';
		$this->configurator->addConfig($configDir . '/common.neon');
		$this->configurator->addConfig($configDir . '/services.neon');
		$this->configurator->addConfig($configDir . '/test.neon');
	}

}

return (new Bootstrap())->bootTests();
