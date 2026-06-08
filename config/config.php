<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// @todo cache config

return (new ConfigAggregator([
    \Laminas\Diactoros\ConfigProvider::class,
    \Laminas\Filter\ConfigProvider::class,
    \Laminas\InputFilter\ConfigProvider::class,
    \Laminas\Validator\ConfigProvider::class,
    \Mezzio\Helper\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Mezzio\Session\ConfigProvider::class,
    \Marshal\Database\ConfigProvider::class,
    \Marshal\Utils\ConfigProvider::class,
    \Marshal\Server\ConfigProvider::class,
    \Marshal\EventManager\ConfigProvider::class,
    \Marshal\Platform\ConfigProvider::class,
    \Marshal\Search\ConfigProvider::class,
    \Marshal\Scheduler\ConfigProvider::class,
    \Marshal\Authentication\ConfigProvider::class,
    new PhpFileProvider(realpath(__DIR__) . '/*{global.php}'),
    new PhpFileProvider(realpath(__DIR__) . '/*{local.php}'),
    new PhpFileProvider(realpath(__DIR__) . '/*{dev.php}'),
]))->getMergedConfig();