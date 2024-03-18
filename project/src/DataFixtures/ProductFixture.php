<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $visibleProduct = new Product(
            'Product 1',
            '/product/1',
            100.00,
            true
        );
        $reflection = new \ReflectionClass($visibleProduct);
        $property = $reflection->getProperty('uuid');
        $property->setValue($visibleProduct, new Uuid('00000000-0000-0000-0000-000000000001'));
        $manager->persist($visibleProduct);

        $nonVisibleProduct = new Product(
            'Product 2',
            '/product/2',
            200.00,
            false
        );
        $reflection = new \ReflectionClass($nonVisibleProduct);
        $property = $reflection->getProperty('uuid');
        $property->setValue($nonVisibleProduct, new Uuid('00000000-0000-0000-0000-000000000002'));
        $manager->persist($nonVisibleProduct);

        $manager->flush();
    }
}