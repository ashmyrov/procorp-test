<?php

namespace App\Repository;

use App\Entity\Product;
use App\Exception\Product\ProductNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $_em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
        $this->_em = $this->getEntityManager();
    }

    public function findByUuid(string $uuid): Product
    {
        $product = $this->findOneBy(['uuid' => $uuid, 'visible' => true]);
        if (!$product) {
            throw new ProductNotFoundException($uuid);
        }
        return $product;
    }

    public function findAllVisible(): array
    {
        return $this->findBy(['visible' => true]);
    }

    public function store(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }
}