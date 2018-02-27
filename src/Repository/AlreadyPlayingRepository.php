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

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('s')
            ->where('s.user = :value')->setParameter('value', $user)
            ->getQuery()
            ->getResult()
            ;
    }


}
