<?php
/**
 * Delivery address entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryAddress class.
 *
 * @ORM\Entity(repositoryClass="App\Repository\DeliveryAddressRepository")
 * @ORM\Table(name="adresy_dostawy")
 */
class DeliveryAddress
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_adresy_dostawy", type="integer")
     */
    private $id;

    /**
     * Actual address.
     *
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return DeliveryAddress
     */
    public function setAddress(AddressBase $address): self
    {
        $this->address = $address;

        return $this;
    }
}
