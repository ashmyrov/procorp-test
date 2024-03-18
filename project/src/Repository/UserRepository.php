<?php

namespace App\Repository;

use App\Entity\Users;
use App\Exception\User\UsernameNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $_em;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
        $this->_em = $this->getEntityManager();
    }

    public function findByUsername(string $username): Users
    {
        $user = $this->findOneBy(['username' => $username]);
        if (!$user) {
            throw new UsernameNotFoundException($username);
        }
        return $user;
    }

    public function store(Users $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}