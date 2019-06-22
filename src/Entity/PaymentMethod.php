<?php
/**
 * PaymentMethod entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PaymentMethod
 *
 * @ORM\Entity(repositoryClass="App\Repository\PaymentMethodRepository")
 * @ORM\Table(name="platnosci")
 */
class PaymentMethod
{
    /**
     * Primary key
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_platnosci", type="integer")
     */
    private $id;

    /**
     * Payment method type
     *
     * @ORM\Column(name="rodzaj_platnosci", type="string", length=25)
     */
    private $type;

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
