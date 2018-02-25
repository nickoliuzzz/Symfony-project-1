<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository2 extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function sortQuery($type="default",$value):array
    {
        $qb = $this->createQueryBuilder('q');
        switch ($type)
        {
            case "default":{
                return $qb
                    ->where('q.id = :value')->setParameter('value', $value)
                    ->orderBy('q.id', 'ASC')
                    ->setMaxResults(5)
                    ->getQuery()
                    ->getResult();
                break;
            }
        }

    }

}
