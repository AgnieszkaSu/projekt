<?php
/**
 * Product entity.
 */
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Product.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="produkty")
 */
class Product
{
    /**
     * Primary key.
     *
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_produktu", type="integer")
     */
    private $id;

    /**
     * Product's price.
     *
     * @ORM\Column(name="cena", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * Product's colour.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Colour")
     * @ORM\JoinColumn(name="kolory_id_koloru", referencedColumnName="id_koloru", nullable=false)
     */
    private $colour;

    /**
     * Product's type.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="products")
     * @ORM\JoinColumn(name="rodzaj_produku_id_rodzaj_produktu", referencedColumnName="id_rodzaj_produktu", nullable=false)
     */
    private $type;

    /**
     * Photos associated with this product.
     *
     * @var \Collection|Photo[]
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="product")
     */
    private $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getColour(): ?Colour
    {
        return $this->colour;
    }

    public function setColour(?Colour $colour): self
    {
        $this->colour = $colour;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }

        return $this;
    }
}
