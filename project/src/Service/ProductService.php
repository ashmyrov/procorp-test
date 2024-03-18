<?php

namespace App\Service;

use App\Dto\Product\CreateDto;
use App\Dto\Product\UpdateDto;
use App\Entity\Product;
use App\Repository\ProductRepository;

readonly class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
    )
    {
    }

    public function getAllVisibleProducts(): array
    {
        return $this->productRepository->findAllVisible();
    }

    public function getProductByUuid(string $uuid): Product
    {
        return $this->productRepository->findByUuid($uuid);
    }

    public function createProduct(CreateDto $createDto): Product
    {
        $product = new Product(
            $createDto->getName(),
            $createDto->getUrl(),
            $createDto->getPrice(),
            $createDto->getVisible()
        );

        $this->productRepository->store($product);

        return $product;
    }

    public function updateProduct(Product $product, UpdateDto $updateDto): Product
    {
        if ($updateDto->getName() !== null) {
            $product->setName($updateDto->getName());
        }

        if ($updateDto->getUrl() !== null) {
            $product->setUrl($updateDto->getUrl());
        }

        if ($updateDto->getPrice() !== null) {
            $product->setPrice($updateDto->getPrice());
        }

        if ($updateDto->getVisible() !== null) {
            $product->setVisible($updateDto->getVisible());
        }

        $this->productRepository->store($product);

        return $product;
    }
}