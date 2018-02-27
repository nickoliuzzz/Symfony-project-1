<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{






    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $text;

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\Answer", mappedBy="question")
     */
    protected $answers;

    /**
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="Quiz", mappedBy="questions")
     */
    protected $quizzes;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    public function addAnswer(Answer $answer): void
    {
        $this->answers->add($answer);
    }

    /**
     * @return mixed
     */
    public function getAnswers()
    {

        return $this->answers;
    }

    /**
     * @param mixed $answers
     */
    public function setAnswers($answers): void
    {
        $this->answers = $answers;
    }


    public function addQuiz(Quiz $quiz) : void
    {
        $this->quizzes->add($quiz);
    }

    /**
     * @return mixed
     */
    public function getQuizzes()
    {
        return $this->quizzes;
    }

    /**
     * @param mixed $quizzes
     */
    public function setQuizzes($quizzes): void
    {
        $this->quizzes = $quizzes;
    }


    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    public function __construct() {
        $this->answers = new ArrayCollection();
        $this->quizzes = new ArrayCollection();
    }
}
