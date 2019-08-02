<?php
/**
 * Customer entity.
 */

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
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_klienta", type="integer")
     */
    private $id;

    /**
     * Surname.
     *
     * @ORM\Column(name="nazwisko", type="string", length=45)
     */
    private $surname;

    /**
     * Name.
     *
     * @ORM\Column(name="imie", type="string", length=45)
     */
    private $name;

    /**
     * Email.
     *
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;

    /**
     * User associated with this customer.
     *
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="customer")
     * @ORM\JoinColumn(name="id_uzytkownicy", referencedColumnName="id_uzytkownicy", nullable=false)
     */
    private $user;

    /**
     * Address associated with this customer.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Address", mappedBy="customer", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $address;

    /**
     * This customer's orders.
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="customer", fetch="EXTRA_LAZY")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

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
     * Gets surname.
     *
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Sets surname.
     *
     * @param string $surname
     *
     * @return Customer
     */
    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Gets name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets name.
     *
     * @param string $name
     *
     * @return Customer
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets email.
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets user.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Sets user.
     *
     * @param User|null $user
     *
     * @return Customer
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets address.
     *
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * Sets address.
     *
     * @param Address|null $address
     *
     * @return Customer
     */
    public function setAddress(?Address $address): self
    {
        $address->setCustomer($this);

        return $this;
    }

    /**
     * Gets orders.
     *
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * Adds order to this customer.
     *
     * @param Order $order
     *
     * @return Customer
     */
    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    /**
     * Removes order from this customer.
     *
     * @param Order $order
     *
     * @return Customer
     */
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
