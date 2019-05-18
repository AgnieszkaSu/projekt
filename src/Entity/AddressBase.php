<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class AddressBase
{
    /**
     * @ORM\Column(name="ulica", type="string", length=45)
     */
    protected $street;

    /**
     * @ORM\Column(name="nr_domu", type="string", length=10)
     */
    protected $number;

    /**
     * @ORM\Column(name="miasto", type="string", length=45)
     */
    protected $city;

    /**
     * @ORM\Column(name="kod_pocztowy", type="integer")
     */
    protected $postal_code;

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postal_code;
    }

    public function setPostalCode(int $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }
}
