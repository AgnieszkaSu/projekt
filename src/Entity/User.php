<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="uzytkownicy",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="login_idx",
 *              columns={"login"}
 *          )
 *      })
 * @UniqueEntity(fields={"login"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_uzytkownicy", type="integer")
     */
    private $id;

    /**
     * @var string $login
     * @ORM\Column(name="login", type="string", length=45)
     *
     * @Assert\NotBlank
     */
    private $login;

    /**
     * @ORM\Column(name="haslo", type="string", length=255)
     *
     * @SecurityAssert\UserPassword
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

    /**
    * {@inheritdoc}
    *
    * @see UserInterface
    *
    * @return string User name
    */
    public function getUsername(): string
    {
        return $this->getLogin();
    }

    /**
    * Getter for the Roles.
    *
    * @return array Roles
    */
    public function getRoles() : array
    {
        $roles[] = $this->getRole()->getName();

        return $roles;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using bcrypt or argon
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
