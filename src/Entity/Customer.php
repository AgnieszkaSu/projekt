<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ORM\Table(name="klienci")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_klienta", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="nazwisko", type="string", length=45)
     */
    private $surname;

    /**
     * @ORM\Column(name="imie", type="string", length=45)
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customer")
     * @ORM\JoinColumn(name="id_uzytkownicy", referencedColumnName="id_uzytkownicy", nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="customer", orphanRemoval=true)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="customer")
     */
    private $orders;

    public function __construct()
    {
        $this->address = new ArrayCollection();
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setCustomer($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getCustomer() === $this) {
                $address->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }
}
