<?php
/**
 * ShippingMethod entity
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShippingMethod class
 *
 * @ORM\Entity(repositoryClass="App\Repository\ShippingMethodRepository")
 * @ORM\Table(name="wysylka")
 */
class ShippingMethod
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_wysylka", type="integer")
     */
    private $id;

    /**
     * Type.
     *
     * @ORM\Column(name="rodzaj_wysylki", type="string", length=45)
     */
    private $type;

    /**
     * Returns id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets type.
     *
     * @return string|null Type
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets type.
     *
     * @param string $type New type
     *
     * @return ShippingMethod
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
