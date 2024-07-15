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
    public function testPushAndPop(): void
    {
        $stack = [];
        $this->assertSame(0, count($stack));
        
        array_push($stack, 'foo'); // permet d'ajouter 'foo' à la fin du tableau
         $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertSame('foo', array_pop($stack)); // array_pop permet de supprimer le dernier élément du tableau
        $this->assertSame(0, count($stack));
    }

}
