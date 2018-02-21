<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
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
     * @ManyToMany(targetEntity="Question", inversedBy="quizzes")
     * @JoinTable(name="question_groups")
     */
    protected $questions;

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param mixed $questions
     */
    public function setQuestions($questions): void
    {
        $this->questions = $questions;
    }






    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="quiz")
     */
    protected $scores;


    /**
     * @return mixed
     */
    public function getScores()
    {
        return $this->scores;
    }


    /**
     * @param mixed $scores
     */
    public function setScores($scores): void
    {
        $this->scores = $scores;
    }






    public function __construct() {
        $this->scores = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->isActive = true;
    }

    public function addScore(Score $score): void
    {
        $this->getScores()->add($score);
    }

    public function addQuestion(Question $question): void
    {
        $this->getQuestions()->add($question);
        $question->addQuiz($this);
    }




    public function removeScore(Score $score): void
    {
        $this->scores->remove($score);
    }

    public function removeQuestion(Question $question): void
    {
        $this->questions->remove($question);
    }



    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @return boolean
     */
    public function getisActive():bool
    {
        return $this->isActive;
    }
    /**
     * @param boolean $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }









    // add your own fields
}
