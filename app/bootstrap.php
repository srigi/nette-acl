<?php

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\SimpleRouter;


// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';


// Enable Nette Debugger for error visualisation & logging
Debugger::$strictMode = TRUE;
Debugger::$logDirectory = __DIR__ . '/../log';
Debugger::enable();


// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config.neon');
$container = $configurator->createContainer();


// Setup router using mod_rewrite detection
$container->router = $router = new RouteList;
$router[] = new Route('index.php', 'Front:Default:default', Route::ONE_WAY);

$router[] = $adminRouter = new RouteList('Admin');
$adminRouter[] = new Route('admin/<presenter>/<action>', 'Default:default');

$router[] = $frontRouter = new RouteList('Front');
$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Default:default');


// Setup session
$session = $container->session;
$session->setExpiration('+ 30 days');
$session->setSavePath(__DIR__ . '/../temp/sessions');
$session->start();


// Configure and run the application!
$application = $container->application;
//$application->catchExceptions = TRUE;
$application->errorPresenter = 'Error';
$application->run();
