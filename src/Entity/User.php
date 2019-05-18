<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="uzytkownicy")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_uzytkownicy", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="login", type="string", length=45)
     */
    private $login;

    /**
     * @ORM\Column(name="haslo", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(name="id_roli", nullable=false)
     */
    private $role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }
}
