<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="uzytkownicy",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="login_idx",
 *              columns={"login"}
 *          )
 *      })
 * @UniqueEntity(fields={"login"}, message="It looks like your already have an account!")
 */
class User implements UserInterface
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_uzytkownicy", type="integer")
     */
    private $id;

    /**
     * Login.
     *
     * @var string $login
     *
     * @ORM\Column(name="login", type="string", length=45)
     *
     * @Assert\NotBlank
     */
    private $login;

    /**
     * Password.
     *
     * @ORM\Column(name="haslo", type="string", length=255)
     */
    private $password;

    /**
     * Role.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(name="id_roli", referencedColumnName="id_roli")
     */
    private $role;

    /**
     * Customer.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Customer", mappedBy="user", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $customer;

    /**
     * Plain password.
     *
     * @Assert\NotBlank
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * Gets id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets login.
     *
     * @return string|null Login
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * Sets login.
     *
     * @param string $login New login
     *
     * @return User
     */
    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Gets password.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets password.
     *
     * @param string $password New password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Gets role.
     *
     * @return Role|null Role
     */
    public function getRole(): ?Role
    {
        return $this->role;
    }

    /**
     * Sets role.
     *
     * @param Role|null $role New role
     *
     * @return User
     */
    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Gets customer.
     *
     * @return Customer|null Customer
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * Sets customer.
     *
     * @param Customer|null $customer New customer
     *
     * @return User
     */
    public function setCustomer(?Customer $customer): self
    {
        $customer->setUser($this);

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
    * Gets roles.
    *
    * @return array Roles
    */
    public function getRoles(): array
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

    /**
     * Gets plain passwords.
     *
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * Sets plain password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPlainPassword(string $password): self
    {
        $this->plainPassword = $password;

        return $this;
    }
}
