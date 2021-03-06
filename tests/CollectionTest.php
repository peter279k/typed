<?php

declare(strict_types=1);

namespace Spatie\Typed\Tests;

use TypeError;
use Spatie\Typed\T;
use Spatie\Typed\Collection;
use Spatie\Typed\Lists\IntegerList;

class CollectionTest extends TestCase
{
    /** @test */
    public function items_of_the_same_type_can_be_added()
    {
        $list = new IntegerList();

        $list[] = 1;

        foreach ($list as $i) {
            $this->assertEquals(1, $i);
        }

        $this->assertEquals(1, $list[0]);
    }

    /** @test */
    public function item_with_different_type_cannot_be_set()
    {
        $this->expectException(TypeError::class);

        $list = new IntegerList();

        $list[] = 'a';
    }

    /** @test */
    public function a_collection_can_have_a_generic_type()
    {
        $list = new Collection(T::generic(Post::class));

        $list[] = new Post();

        $this->assertInstanceOf(Post::class, $list[0]);

        $this->expectException(TypeError::class);

        $list[] = new Wrong();
    }

    /** @test */
    public function collections_can_contain_collections()
    {
        $listOfLists = new Collection(T::generic(Collection::class));

        $listOfLists[] = new Collection(T::string(), ['a', 'b']);

        $listOfLists[] = new Collection(T::int(), [1, 2]);

        $listOfLists[0][0] = 'c';

        $this->expectException(TypeError::class);

        $listOfLists[0][0] = new Wrong();
    }

    /** @test */
    public function collection_can_contain_nullable_types()
    {
        $list = new Collection(T::nullable(T::int()));

        $list[] = null;
        $list[] = null;

        foreach ($list as $i) {
            $this->assertEquals(null, $i);
        }

        $list[] = 1;

        $this->assertEquals(null, $list[0]);
        $this->assertEquals(null, $list[1]);
        $this->assertEquals(1, $list[2]);
    }

    /** @test */
    public function it_offset_exists()
    {
        $list = new Collection(T::nullable(T::int()));

        $list[] = null;
        $list[] = null;

        $this->assertTrue($list->offsetExists(0));
        $this->assertTrue($list->offsetExists(1));
        $this->assertFalse($list->offsetExists(2));
    }

    /** @test */
    public function it_offset_unset_can_unset_value()
    {
        $list = new Collection(T::nullable(T::int()));

        $list[] = null;
        $list[] = null;

        $list->offsetUnset(0);

        $this->assertFalse($list->offsetExists(0));
    }

    /** @test */
    public function it_can_return_current_position()
    {
        $list = new Collection(T::nullable(T::int()));

        $list[] = null;
        $list[] = null;

        $this->assertSame(0, $list->key());

        $list->next();

        $this->assertSame(1, $list->key());
    }

    /** @test */
    public function it_can_let_collection_return_array()
    {
        $list = new Collection(T::int());

        $list[] = 1;
        $list[] = 2;
        $list[] = 3;

        $this->assertSame([1, 2, 3], $list->toArray());
    }
}
