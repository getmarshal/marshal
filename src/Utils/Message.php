<?php

declare(strict_types=1);

namespace Marshal\Utils;

use Locale;
use MessageFormatter;

final class Message
{
    public static function get(string $key, ?string $locale = null, array $args = []): string
    {
        $messages = Config::get('messages');
        $locale = $locale ? $locale : Locale::getDefault();
        if (! isset($messages[$locale][$key])) {
            throw new \InvalidArgumentException(\sprintf(
                "Message %s not found",
                $key
            ));
        }

        $formatter = MessageFormatter::create($locale, $messages[$locale][$key]);
        return $formatter->format($args);
    }
}
