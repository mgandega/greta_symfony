<?php

namespace App\Tests\UnitTest;

use PHPUnit\Framework\TestCase;

class DependencyTest extends TestCase
{
    /******** dependence unique *******/
    public function testEmpty(): array
    {
        $stack = [];
        $this->assertEmpty($stack);

        return $stack; // []
    }

    /**
     * @depends testEmpty
     */
    public function testPush(array $stack): array
    {
        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertNotEmpty($stack);

        return $stack; // ['foo']
    }

    /**
     * @depends testPush
     */
    public function testPop(array $stack): void
    {
        $this->assertSame('foo', array_pop($stack)); 
        $this->assertEmpty($stack);
    }

    /********** dependence multiple **************/

    public function testProducerFirst(): string
    {
        $this->assertTrue(true);

        return 'first';
    }

    public function testProducerSecond(): string
    {
        $this->assertTrue(true);

        return 'second';
    }

    /**
     * @depends testProducerFirst
     * @depends testProducerSecond
     */
    public function testConsumer(string $a, string $b): void
    {
        $this->assertSame('first', $a);
        $this->assertSame('second', $b);
    }

}
