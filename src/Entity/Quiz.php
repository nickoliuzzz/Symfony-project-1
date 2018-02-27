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
     * @ManyToMany(targetEntity="Question", inversedBy="quizzes")
     * @JoinTable(name="question_groups")
     */
    protected $questions;

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="quiz")
     */
    protected $scores;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $name;


    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->isActive = true;
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


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

    public function removeQuestion(Question $question): void
    {
        $this->questions->remove($question);
    }

    public function addQuestion(Question $question): void
    {
        $this->getQuestions()->add($question);
        $question->addQuiz($this);
    }


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

    public function addScore(Score $score): void
    {
        $this->getScores()->add($score);
    }

    public function removeScore(Score $score): void
    {
        $this->scores->remove($score);
    }



    /**
     * @return boolean
     */
    public function getisActive(): bool
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


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
