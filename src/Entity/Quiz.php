<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="quiz")
     */
    private $questions;

    /**
     * @return string
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param string $questions
     */
    public function setQuestions($questions): void
    {
        $this->questions = $questions;
    }






    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="Quiz")
     */
    private $scores;


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
    }

    public function addScore(Score $score): void
    {
        $this->scores=$score;
    }

    public function addQuestion(Question $question): void
    {
        $this->questions=$question;
    }

    public function removeScore(Score $score): void
    {
        $this->scores->remove($score);
    }

    public function removeQuestion(Question $question): void
    {
        $this->questions->remove($question);
    }










    // add your own fields
}
