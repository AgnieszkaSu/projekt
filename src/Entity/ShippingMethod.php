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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_wysylka", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="rodzaj_wysylki", type="string", length=45)
     */
    private $type;

    /**
     * Returns id.
     *
     * @return int|null Id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
