<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\AlreadyPlaying;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $isActive = array("IS_AUTHENTICATED_ANONYMOUSLY");

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\AlreadyPlaying", mappedBy="user")
     */
    private $alreadyplaings;


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }


    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }


    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }


    /**
     * @return array
     */
    public function getAlreadyplaings()
    {
        return $this->alreadyplaings;
    }

    /**
     * @param array $alreadyplaings
     */
    public function setAlreadyplaings($alreadyplaings): void
    {
        $this->alreadyplaings = $alreadyplaings;
    }

    public function addAlreadyplaings($alreadyplaing): void
    {
        return;
    }

    public function removeAlreadyplaings(AlreadyPlaying $alreadyplaing): void
    {
        $this->alreadyplaings->removeElement($alreadyplaing);
    }


    public function getRoles()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setRoles($isActive): void
    {
        $this->isActive = $isActive;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
    }
}