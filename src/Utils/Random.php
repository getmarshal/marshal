<?php

declare(strict_types=1);

namespace Marshal\Utils;

final class Random
{
    public static function generateTag(
        int $length = 9,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \InvalidArgumentException("Length must be a positive integer");
        }

        $pieces = [];
        $max = \mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[\random_int(0, $max)];
        }

        return \implode('', $pieces);
    }
}
