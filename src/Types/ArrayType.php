<?php declare(strict_types=1);

namespace Typed\Types;

final class ArrayType implements Type
{
    public function __invoke(array $value): array
    {
        return $value;
    }
}
