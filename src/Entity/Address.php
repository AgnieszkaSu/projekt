<?php
/**
 * Address entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Address.
 *
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @ORM\Table(name="adresy")
 */
class Address
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_adresu", type="integer")
     */
    private $id;

    /**
     * Customer associated with this address.
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Customer", inversedBy="address")
     * @ORM\JoinColumn(name="id_klienta", referencedColumnName="id_klienta", nullable=false)
     */
    private $customer;

    /**
     * Actual address.
     *
     * @ORM\Embedded(class="AddressBase", columnPrefix=false)
     */
    private $address;

    /**
     * Address constructor.
     */
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

    /**
     * Gets customer.
     *
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * Sets customer.
     *
     * @param Customer|null $customer
     *
     * @return Address
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Gets address.
     *
     * @return AddressBase
     */
    public function getAddress(): AddressBase
    {
        return $this->address;
    }

    /**
     * Sets address.
     *
     * @param AddressBase $address
     *
     * @return Address
     */
    public function setAddress(AddressBase $address): self
    {
        $this->address = $address;

        return $this;
    }
}
