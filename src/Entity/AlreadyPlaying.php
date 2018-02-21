<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use app\Entity\User;
use app\Entity\Quiz;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlreadyPlayingRepository")
 */
class AlreadyPlaying
{
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="alreadyplaings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

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
     * One AlreadyPlaying has One Quiz.
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    protected $quiz;


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
     * @ORM\Column(type="integer")
     */
    private $numberOfCorrectAnswers = 0;

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


    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfAnswers = 0;

    /**
     * @return integer
     */
    public function getNumberOfAnswers()
    {
        return $this->numberOfAnswers;
    }

    /**
     * @param integer $numberOfAnswers
     */
    public function setNumberOfAnswers($numberOfAnswers): void
    {
        $this->numberOfAnswers = $numberOfAnswers;
    }


}
