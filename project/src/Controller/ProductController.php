<?php

namespace App\Controller;

use App\Dto\Product\CreateDto;
use App\Dto\Product\UpdateDto;
use App\Exception\Product\ProductNotFoundException;
use App\Service\ProductService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/products', name: 'api_products')]
class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly LoggerInterface $logger
    )
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productService->getAllVisibleProducts();
        return $this->json($products);
    }

    #[Route('/{uuid}', name: 'show', methods: ['GET'])]
    public function show(string $uuid): JsonResponse
    {
        try {
            $product = $this->productService->getProductByUuid($uuid);
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($product);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateDto $createDto): JsonResponse
    {
        try {
            $product = $this->productService->createProduct($createDto);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->json($product, Response::HTTP_CREATED);
    }

    #[Route('/{uuid}', name: 'update', methods: ['PUT'])]
    public function update(#[MapRequestPayload] UpdateDto $updateDto, string $uuid): JsonResponse
    {
        try {
            $product = $this->productService->getProductByUuid($uuid);
            $updatedProduct = $this->productService->updateProduct($product, $updateDto);
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($updatedProduct);
    }
}