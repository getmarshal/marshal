<?php

declare(strict_types=1);

namespace Marshal\Utils;

use Laminas\Stdlib\ArrayUtils\MergeRemoveKey;
use Laminas\Stdlib\ArrayUtils\MergeReplaceKeyInterface;

final class ArrayUtils
{
    public static function mergeArray(array $a, array $b): array
    {
        foreach ($b as $key => $value) {
            if ($value instanceof MergeReplaceKeyInterface) {
                $a[$key] = $value->getData();
            } elseif (isset($a[$key]) || \array_key_exists($key, $a)) {
                if ($value instanceof MergeRemoveKey) {
                    unset($a[$key]);
                } elseif (\is_int($key)) {
                    $a[] = $value;
                } elseif (\is_array($value) && \is_array($a[$key])) {
                    $a[$key] = self::mergeArray($a[$key], $value);
                } else {
                    $a[$key] = $value;
                }
            } else {
                if (! $value instanceof MergeRemoveKey) {
                    $a[$key] = $value;
                }
            }
        }
        return $a;
    }
}
