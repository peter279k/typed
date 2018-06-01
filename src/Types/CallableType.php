<?php

declare(strict_types=1);

namespace Spatie\Typed\Types;

use Spatie\Typed\Type;

final class CallableType implements Type
{
    use Nullable;

    public function __invoke(callable $value): callable
    {
        return $value;
    }

    public function __toString(): string
    {
        return 'callable';
    }
}
