<?php

declare(strict_types=1);

namespace Spatie\Typed\Tests;

use Spatie\Typed\Tests\Extra\Post;
use Spatie\Typed\Tests\Extra\Wrong;
use TypeError;
use Spatie\Typed\T;
use Spatie\Typed\Collection;
use Spatie\Typed\Types\ArrayType;
use Spatie\Typed\Types\FloatType;
use Spatie\Typed\Types\StringType;
use Spatie\Typed\Types\BooleanType;
use Spatie\Typed\Types\GenericType;
use Spatie\Typed\Types\IntegerType;
use Spatie\Typed\Types\CallableType;
use Spatie\Typed\Types\CollectionType;

class TypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider successProvider
     */
    public function successful_types($type, $value)
    {
        $collection = new Collection($type);

        $collection[] = $value;

        $this->assertCount(1, $collection);
    }

    /**
     * @test
     * @dataProvider failProvider
     */
    public function wrong_types_throw_type_errors($type, $value)
    {
        $this->expectException(TypeError::class);

        $collection = new Collection($type);

        $collection[] = $value;
    }

    public function successProvider()
    {
        return [
            [new ArrayType(), ['a']],
            [new BooleanType(), true],
            [new CallableType(), function () {
            }],
            [new CollectionType(), new Collection(new ArrayType())],
            [new FloatType(), 1.1],
            [new GenericType(Post::class), new Post()],
            [new IntegerType(), 1],
            [new StringType(), 'a'],
            [T::nullable(T::string()), 'a'],
            [T::nullable(T::collection()), null],
        ];
    }

    public function failProvider()
    {
        return [
            [ArrayType::class, new Wrong()],
            [BooleanType::class, new Wrong()],
            [CallableType::class, new Wrong()],
            [CollectionType::class, new Wrong()],
            [FloatType::class, new Wrong()],
            [new GenericType(Post::class), new Wrong()],
            [IntegerType::class, new Wrong()],
            [StringType::class, new Wrong()],
            [T::nullable(T::string()), new Wrong()],
        ];
    }
}
