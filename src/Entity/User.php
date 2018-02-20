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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;


    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="App\Entity\AlreadyPlaying", mappedBy="user")
     */
    private $alreadyplaings;


    /**
     * @return array
     */
    public function getAlreadyplaings(): array
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
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return;
    }

    public function removeAlreadyplaings(AlreadyPlaying $alreadyplaing): void
    {
        $this->alreadyplaings->removeElement($alreadyplaing);
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
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
        // TODO: Implement eraseCredentials() method.
    }

    // other methods, including security methods like getRoles()

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
}