<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $user = (new Users)
            ->setUsername('user')
            ->setPassword('password');
        $manager->persist($user);
        $manager->flush();
    }
}