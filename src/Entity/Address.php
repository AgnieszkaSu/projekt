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
     * Gets id.
     *
     * @return int|null Id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
