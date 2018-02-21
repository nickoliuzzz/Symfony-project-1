<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
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
    public function getId(): integer
    {
        return $this->id;
    }


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    protected $question;

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }


    /**
     * @ORM\Column(type="string")
     */
    private $text;


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


    /**
     * @ORM\Column(type="boolean")
     */
    private $isTrue;

    /**
     * @return mixed
     */
    public function getisTrue()
    {
        return $this->isTrue;
    }

    /**
     * @param mixed $isTrue
     */
    public function setIsTrue($isTrue): void
    {
        $this->isTrue = $isTrue;
    }


    // add your own fields
}
