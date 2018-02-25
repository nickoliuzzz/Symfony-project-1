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
    public function sortQuery($sortType,$value,$pageNumber):array
    {
        $order = null;
        $sortType = abs($sortType);
        if($sortType > 0){
            $order = 'ASC';
        }
        else {
            $order = 'DESC';
        }

        $qb = $this->createQueryBuilder('q');
        if('' != trim($value)) {
            $qb
                ->where('q.email LIKE :value OR q.username LIKE :value OR q.id = :number ');
               $qb ->setParameter('value' , '%'.$value.'%');
               if(is_numeric($value))
               {
                   $qb->setParameter( 'number' , (int)$value  );
               }
               else $qb->setParameter( 'number' ,  -1 );

        }

        switch ($sortType)
        {//DESC ASC
            case 1:{
                $qb
                    ->orderBy('q.id', $order);
                break;
            }
            case 2:{
                $qb
                    ->orderBy('q.email', $order);
                break;
            }
            case 3:{
                $qb
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
