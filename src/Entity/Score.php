<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{

    public function __construct()
    {
        $this->user = new User();
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz" , inversedBy="scores")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    /**
     * @return mixed
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * @param mixed $quiz
     */
    public function setQuiz($quiz): void
    {
        $this->quiz = $quiz;
    }


    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }






    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfCorrectAnswers;

    /**
     * @return integer
     */
    public function getNumberOfCorrectAnswers()
    {
        return $this->numberOfCorrectAnswers;
    }

    /**
     * @param integer $numberOfCorrectAnswers
     */
    public function setNumberOfCorrectAnswers($numberOfCorrectAnswers): void
    {
        $this->numberOfCorrectAnswers = $numberOfCorrectAnswers;
    }


}
