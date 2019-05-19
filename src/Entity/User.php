<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\JoinColumn(name="id_roli", referencedColumnName="id_roli", nullable=false)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="user", orphanRemoval=true)
     */
    private $customer;

    public function __construct()
    {
        $this->customer = new ArrayCollection();
    }

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

    /**
     * @return Collection|Customer[]
     */
    public function getCustomer(): Collection
    {
        return $this->customer;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customer->contains($customer)) {
            $this->customer[] = $customer;
            $customer->setUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customer->contains($customer)) {
            $this->customer->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

        return $this;
    }
}
