<?php

declare(strict_types=1);

namespace Marshal\Utils\Python;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Marshal\Utils\Config;
use Marshal\Utils\Logger\LoggerManager;

final class Python
{
    private const string DEFAULT_HOST = 'http://localhost:5000';

    public static function run(string $script, array $args = []): string
    {
        $config = Config::get('python');
        if (! isset($config['scripts'][$script])) {
            throw new \InvalidArgumentException("Script $script config not found in config");
        }

        $scriptConfig = $config['scripts'][$script];
        self::validate($script, $scriptConfig);

        // get additional paths
        $paths = [];
        $app = \explode('::', $script)[0];
        if (isset($config['paths'][$app])) {
            foreach ($config['paths'][$app] as $path) {
                $paths[] = $path;
            }
        }

        // prepare the payload
        $payload = \json_encode([
            'module' => $scriptConfig['module'],
            'function' => $scriptConfig['function'],
            'args' => \json_encode($args),
            'paths' => \json_encode($paths),
        ]);

        $client = new Client();
        $request = new Request('POST', $config['host'] ?? self::DEFAULT_HOST, [
            'content-type' => 'application/json',
        ], $payload);
        $response = $client->sendRequest($request);
        if (200 !== $response->getStatusCode()) {
            LoggerManager::get()->warning("Invalid python brigde response: {$response->getReasonPhrase()}");
            return "";
        }

        return $response->getBody()->getContents();
    }

    private static function validate(string $script, mixed $config): void
    {
        // @todo validation messages
        if (! \is_array($config)) {
            throw new \InvalidArgumentException("Python script $script config must be valid array");
        }

        if (! isset($config['module'])) {
            throw new \InvalidArgumentException("Python script $script module not specified");
        }

        if (! isset($config['function'])) {
            throw new \InvalidArgumentException("Python script $script function not specified");
        }
    }
}
