<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShippingAddressRepository")
 * @ORM\Table(name="adresy_dostawy")
 */
class ShippingAddress extends AddressBase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_adresy_dostawy", type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}