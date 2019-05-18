<?php
/**
 * Photo entity.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Photo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(name="galeria_zdjec")
 */
class Photo
{
    /**
     * Primary key.
     *
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_zdj", type="integer")
     */
    private $id;

    /**
     * Photo location.
     *
     * @var string
     * @ORM\Column(name="nazwa_jpg", type="string", length=45)
     */
    private $location;

    /**
     * Products associated with this picture.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="photos")
     * @ORM\JoinColumn(name="id_produktu", referencedColumnName="id_produktu")
     */
    private $product;

    /**
     * Returns id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return Photo
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * @param Product|null $product
     * @return Photo
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
