<?php

use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\SimpleRouter;


// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';


// Configure application
$configurator = new Nette\Config\Configurator;


// Enable Nette Debugger for error visualisation & logging
//$configurator->setProductionMode($configurator::AUTO);
$configurator->enableDebugger(__DIR__ . '/../log');


// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();


// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();


// Setup router using mod_rewrite detection
if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
	$container->router[] = new Route('index.php', 'Front:Default:default', Route::ONE_WAY);

	$container->router[] = $adminRouter = new RouteList('Admin');
	$adminRouter[] = new Route('admin/<presenter>/<action>[/<id>]', 'Default:default');

	$container->router[] = $frontRouter = new RouteList('Front');
	$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Default:default');

} else {
	$container->router = new SimpleRouter('Front:Default:default');
}


// Configure and run the application!
$container->application->run();
