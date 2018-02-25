<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VerificationEmailRepository")
 */
class VerificationEmail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=false)
     */
    private $verificationString;

    /**
     * @ORM\Column(type="boolean")
     */
    private $forgot=0;

    /**
     * One Product has One Shipment.
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
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
     * @return mixed
     */
    public function getVerificationString()
    {
        return $this->verificationString;
    }

    /**
     * @param mixed $verificationString
     */
    public function setVerificationString($verificationString): void
    {
        $this->verificationString = $verificationString;
    }

    /**
     * @return mixed
     */
    public function getForgot()
    {
        return $this->forgot;
    }

    /**
     * @param mixed $forgot
     */
    public function setForgot($forgot): void
    {
        $this->forgot = $forgot;
    }

    // add your own fields
}
