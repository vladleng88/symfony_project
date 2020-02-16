<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="У вас уже есть аккаунт")
 */
class User implements UserInterface
{

    public const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $plainPassword;

    /**
     * @var array
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     *
     */
    private $confirmationCode;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     *
     */
    private $enable;


    public function __construct()
    {
        $this->roles = [self::ROLE_USER];
        $this->enabled = false;
    }



    public function getRoles()
    {
        return $this->roles;
    }
    public function setRoles($roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function getPassword() :string
    {
        return $this->password;
    }
    public function setPassword ($password)
    {
        $this->password = $password;
        return $this;
    }
    public function getSalt()
    {
        return null;
    }
    public function getUsername()
    {
        return $this->email;
    }
    public function eraseCredentials()
    {
        return $this->plainPassword = null;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail ($email)
    {
        $this->email = $email;
        return $this;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword ($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
    public function getConfirmationCode()
    {
        return $this->confirmationCode;
    }
    public function setConfirmationCode(string $confirmationCode)
    {
        $this->confirmationCode = $confirmationCode;
        return $this;
    }
    public function getEnable()
    {
        return $this->enable;
    }
    public function setEnable(bool $enable)
    {
        $this->enable = $enable;
        return $this;
    }

}
