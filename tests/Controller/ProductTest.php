<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testAdd(ProductRepository $productRepository): void
    {
        $product = new Product();

        $this->assertTrue(true);
    }
}
