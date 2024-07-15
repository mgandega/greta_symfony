<?php

namespace App\Tests\UnitTest;

use PHPUnit\Framework\TestCase;

class DataProvidersTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testAdd(int $expected, int $a, int $b): void
    {

        $this->assertSame($expected, $a + $b);
    }


    public function additionProvider(): array
    {
        return [
            [0, 0, 0],
            [2, 1, 1],
            [1, 0, 1],
            [3, 1, 2]
        ];
    }
}
