<?php

declare(strict_types=1);

namespace Marshal\EventManager;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class LazyListenerProvider implements ListenerProviderInterface
{
    public const int PRIORIY_HIGH = 2;
    public const int PRIORITY_LOW = 0;
    public const int PRIORITY_MAX = \PHP_INT_MAX;
    public const int PRIORITY_MIN = \PHP_INT_MIN;
    public const int PRIORITY_NORMAL = 1;

    public function __construct(private ContainerInterface $container, private array $config)
    {
    }

    public function getListenersForEvent(object $event): array|\Traversable
    {
        $listeners = [];
        $eventName = \get_class($event);

        foreach ($this->config['listeners'] ?? [] as $listener => $events) {
            foreach ($events as $name => $config) {
                if ($name !== $eventName) {
                    continue;
                }


                if (! isset($config['listener'])) {
                    throw new \InvalidArgumentException("Listener $listener config has no listener");
                }

                try {
                    $instance = $this->container->get($listener);
                } catch (\Throwable $e) {
                    throw new Exception\ListenerNotFoundException($e);
                }

                if (! \method_exists($instance, $config['listener'])) {
                    throw new \InvalidArgumentException("Listener not found");
                }

                $listeners[] = [
                    'listener' => [$instance, $config['listener']],
                    'priority' => isset($config['priority']) && \is_int($config['priority'])
                        ? $config['priority']
                        : self::PRIORITY_NORMAL,
                ];
            }
        }

        // sort by priority
        \usort($listeners, function ($a, $b): int {
            if ($a['priority'] === $b['priority']) {
                return 0;
            }

            return ($a['priority'] > $b['priority']) ? -1 : 1;
        });

        return \array_column($listeners, 'listener');
    }
}
