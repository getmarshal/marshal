<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use Marshal\Utils\ArrayUtils;
use Marshal\Utils\Config;
use Marshal\EventManager\LazyListenerProvider;
use Psr\EventDispatcher\ListenerProviderInterface;
use Marshal\Database\DatabaseManager;
use Marshal\EventManager\EventDispatcher;

// load configuration
$global = require __DIR__ . '/config.php';

$mergedConfig = [];
foreach ($global['apps'] ?? [] as $appConfig) {
    if (! isset($appConfig["configFile"])) {
        continue;
    }

    $appConfig = require $appConfig["configFile"];
    $mergedConfig = ArrayUtils::mergeArray($global, $appConfig);
}

// initialize the global config object
Config::initialize($mergedConfig);

// set the configuration as a service
$dependencies = $mergedConfig['dependencies'];
$dependencies['services']['config'] = $mergedConfig;

// create the container
$serviceManager = new ServiceManager($dependencies);

// set the event listener service
$listenerProvider = new LazyListenerProvider($serviceManager, $mergedConfig['events'] ?? []);
$serviceManager->setAllowOverride(TRUE);
$serviceManager->setService(ListenerProviderInterface::class, $listenerProvider);
$serviceManager->setAllowOverride(FALSE);

// init the event manager onto the database manager
DatabaseManager::setEventDispatcher(new EventDispatcher($listenerProvider));

return $serviceManager;
