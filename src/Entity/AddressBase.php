<?php
/**
 * Address base abstract class.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddressBase class.
 *
 * AddressBase class.
 *
 * @ORM\Embeddable
 */
class AddressBase
{
    /**
     * Street name.
     *
     * @ORM\Column(name="ulica", type="string", length=45)
     */
    protected $street;

    /**
     * House number.
     *
     * @ORM\Column(name="nr_domu", type="string", length=10)
     */
    protected $number;

    /**
     * City name.
     *
     * @ORM\Column(name="miasto", type="string", length=45)
     */
    protected $city;

    /**
     * Postal code.
     *
     * @ORM\Column(name="kod_pocztowy", type="integer")
     */
    protected $postalCode;

    /**
     * Gets street.
     *
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * Sets street.
     *
     * @param string $street
     *
     * @return AddressBase
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get house number.
     *
     * @return string|null House number
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * Sets house number.
     *
     * @param string $number New house number
     *
     * @return AddressBase
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Gets city.
     *
     * @return string|null City
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Sets city.
     *
     * @param string $city New city
     *
     * @return AddressBase
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Gets postal code.
     *
     * @return int|null Postal code
     */
    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    /**
     * Sets postal code.
     *
     * @param int $postalCode New postal code
     *
     * @return AddressBase
     */
    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }
}
