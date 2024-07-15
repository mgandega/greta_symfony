<?php

namespace App\Tests\UnitTest;

use PHPUnit\Framework\TestCase;
use App\Controller\ProductController;

class ProductTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);       
    }

    public function testProduct(){
        $product = new ProductController;
        $resultat = $product->calculTva(200, 'food');
        $this->assertEquals(100, $resultat);
    }
}
