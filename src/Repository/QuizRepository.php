<?php

namespace App\Repository;

use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class QuizRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quiz::class);
    }


    public function findBySomething($value)
    {
        return $this->createQueryBuilder('q')
            ->setFirstResult($value)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }
    public function getNumberofQuiz()
    {
        return count($this->findAll());
    }
    public function sortQuery($sortType,$value,$pageNumber, $order="ASC"):array
    {
        $qb = $this->createQueryBuilder('q');
        switch ($sortType)
        {//DESC ASC
            case 1:{
                $qb
                    ->where('q.name LIKE :value OR q.id LIKE :value ')
                    ->setParameter('value', $value.'%')
                    ->orderBy('q.id', $order);
                break;
            }
            case 2:{
                $qb
                    ->where('q.name LIKE :value OR q.id LIKE :value ')
                    ->setParameter('value', $value.'%')
                    ->orderBy('q.name', $order);
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
