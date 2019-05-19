<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 * @ORM\Table(name="adresy")
 */
class Address extends AddressBase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_adresu", type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="address")
     * @ORM\JoinColumn(name="id_klienta", referencedColumnName="id_klienta", nullable=false)
     */
    private $customer;

    /**
     * Gets id.
     *
     * @return int|null Id.
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
}
