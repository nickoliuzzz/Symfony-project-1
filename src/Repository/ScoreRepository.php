<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Entity\Score;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Score::class);
    }


    public function findByQuiz(Quiz $quiz)
    {
        return $this->createQueryBuilder('s')
            ->where('s.quiz = :value')->setParameter('value', $quiz)
            ->orderBy('s.numberOfCorrectAnswers')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function isUserAlreadyPlay(Quiz $quiz, User $user):bool
    {
        $isUser = $this->createQueryBuilder('s')
            ->where('s.quiz = :value')->setParameter('value', $quiz)
            ->andWhere('s.user = :val')->setParameter('val',$user )
            ->orderBy('s.numberOfCorrectAnswers')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
         if(count($isUser) === 1) return true;
        else return false;

    }
    public function getTopUser(Quiz $quiz, int $numberUser)
    {
        return $this->createQueryBuilder('s')
            ->where("s.quiz =: value")->setParameter('value', $quiz)
            ->orderBy("s.numberOfCorrectAnswers","DESC")
            ->setMaxResults($numberUser)
            ->getQuery()
            ->getResult();
    }
}
