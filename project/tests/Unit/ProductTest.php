<?php

namespace Unit;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testCreateProduct()
    {
        $product = new Product(
            'Product 1',
            '/product/1',
            100.00,
            true
        );

        $this->assertSame('Product 1', $product->getName());
        $this->assertSame('/product/1', $product->getUrl());
        $this->assertSame(100.00, $product->getPrice());
        $this->assertSame(true, $product->isVisible());
    }

    public function testUpdateProduct()
    {
        $product = new Product(
            'Product 1',
            '/product/1',
            100.00,
            true
        );

        $product->setName('Product 2');
        $product->setUrl('/product/2');
        $product->setPrice(200.00);
        $product->setVisible(false);

        $this->assertSame('Product 2', $product->getName());
        $this->assertSame('/product/2', $product->getUrl());
        $this->assertSame(200.00, $product->getPrice());
        $this->assertSame(false, $product->isVisible());
    }
}