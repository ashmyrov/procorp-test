<?php

namespace Integration;

use App\Dto\Product\CreateDto;
use App\Dto\Product\UpdateDto;
use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductServiceTest extends KernelTestCase
{
    private ProductService $productService;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->productService = self::getContainer()->get(ProductService::class);
    }

    public function testCreateProduct()
    {
        $createDto = (new CreateDto)
            ->setName('New Product')
            ->setUrl('product/url')
            ->setPrice(400)
            ->setVisible(true);

        $product = $this->productService->createProduct($createDto);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertNotNull($product->getUuid());
        $this->assertSame('New Product', $product->getName());
        $this->assertSame('product/url', $product->getUrl());
        $this->assertSame(400.0, $product->getPrice());
        $this->assertSame(true, $product->isVisible());
    }

    public function testUpdateProduct()
    {
        $uuid = '00000000-0000-0000-0000-000000000001';
        $product = $this->productService->getProductByUuid($uuid);
        $this->assertSame('00000000-0000-0000-0000-000000000001', $product->getUuid()->toRfc4122());
        $this->assertSame('Product 1', $product->getName());
        $this->assertSame('/product/1', $product->getUrl());
        $this->assertSame(100.0, $product->getPrice());
        $this->assertSame(true, $product->isVisible());

        $updateDto = (new UpdateDto())
            ->setName('Updated Product')
            ->setUrl('updated/url')
            ->setPrice(300)
            ->setVisible(false);


        $updatedProduct = $this->productService->updateProduct($product, $updateDto);

        $this->assertSame('00000000-0000-0000-0000-000000000001', $product->getUuid()->toRfc4122());
        $this->assertSame('Updated Product', $updatedProduct->getName());
        $this->assertSame('updated/url', $updatedProduct->getUrl());
        $this->assertSame(300.0, $updatedProduct->getPrice());
        $this->assertSame(false, $updatedProduct->isVisible());
    }
}