<?php
/**
 * Photo entity.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Photo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(
 *      name="galeria_zdjec",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="UQ_photos_1",
 *              columns={"nazwa_jpg"},
 *          ),
 *     },
 * )
 * @UniqueEntity(fields={"location"})
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
     * @ORM\Column(name="nazwa_jpg", type="string", length=45, unique=true)
     *
     * @Assert\NotBlank
     * @Assert\Image(
     *     maxSize = "3M",
     * )
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
     * Gets location.
     *
     * @return mixed|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets location.
     *
     * @param mixed $location
     *
     * @return Photo
     */
    public function setLocation($location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Gets product.
     *
     * @return Product|null
     */
    public function getProduct(): ?Product
    {
        return $this->product;
    }

    /**
     * Sets product.
     *
     * @param Product|null $product
     *
     * @return Photo
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
