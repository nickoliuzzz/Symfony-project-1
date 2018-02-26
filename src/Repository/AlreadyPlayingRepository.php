<?php

namespace App\Repository;

use App\Entity\AlreadyPlaying;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class AlreadyPlayingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AlreadyPlaying::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('s')
            ->where('s.user = :value')->setParameter('value', $user)
            ->getQuery()
            ->getResult()
            ;
    }


}
