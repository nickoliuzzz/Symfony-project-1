<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr\Comparison as KEK;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }


    /**
     * @KEK\
     */
    public function sortQuery($sortType,$value,$pageNumber, $order="ASC"):array
    {
        $qb = $this->createQueryBuilder('q');
        switch ($sortType)
        {//DESC ASC
            case 1:{
                $qb
                    ->where('q.email LIKE :value OR q.username LIKE :value ')
                    ->setParameter('value', $value.'%')
                    ->orderBy('q.id', $order);
                break;
            }
            case 2:{
                $qb
                    //->where('q.id = :value')->setParameter('value', $value)
                    ->where('q.email LIKE :value OR q.username LIKE :value ')
                    ->setParameter('value', $value.'%')
                    ->orderBy('q.email', $order);
                break;
            }
            case 3:{
                $qb
                    //->where('q.id = :value')->setParameter('value', $value)
                    ->where('q.email LIKE :value OR q.username LIKE :value ')
                    ->setParameter('value', $value.'%')
                    ->orderBy('q.username', $order);
                break;
            }
            default:{
                break;
                }
        }
        return $qb
            ->setMaxResults(5)
            ->setFirstResult($pageNumber*5)
            ->getQuery()
            ->getResult();
    }
}
