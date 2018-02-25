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

}
