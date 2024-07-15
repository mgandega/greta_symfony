<?php

namespace App\Tests\UnitTest;

use App\Controller\ProductController;
use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    public function testException(): void
    {
        $produit = new ProductController;
        $this->expectException('Exception');
        $produit->calculTva(200,'machine');

        // $this->assertTrue(true);
    }
}
