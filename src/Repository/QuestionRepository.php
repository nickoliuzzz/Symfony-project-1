<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('q')
            ->where('q.something = :value')->setParameter('value', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function sortQuery($sortType,$value,$pageNumber):array
    {
        $order = null;
        if($sortType > 0){
            $order = 'ASC';
        }
        else {
            $order = 'DESC';
        }
        $sortType = abs($sortType);
        $qb = $this->createQueryBuilder('q');
        if('' != trim($value)) {
            $qb
                ->where('q.text LIKE :value OR q.id = :number ');
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
                    ->orderBy('q.text', $order);
                break;
            }
            case 3:{
                $qb
                ->orderBy('q.answers', $order);
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
