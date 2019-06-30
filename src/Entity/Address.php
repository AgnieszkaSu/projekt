<?php
/**
 * Address entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @ORM\Table(name="adresy")
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_adresu", type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Customer", inversedBy="address")
     * @ORM\JoinColumn(name="id_klienta", referencedColumnName="id_klienta", nullable=false)
     */
    private $customer;

    /**
     * @ORM\Embedded(class="AddressBase", columnPrefix=false)
     */
    private $address;

    public function __construct()
    {
        $this->address = new AddressBase();
    }

    /**
     * Gets id.
     *
     * @return int|null id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getAddress(): AddressBase
    {
        return $this->address;
    }

    public function setAddress(AddressBase $address): self
    {
        $this->address = $address;

        return $this;
    }
}
