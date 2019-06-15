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
     * Decimal w bazie danych -> doctrine zwraca string
     *
     * @var string
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
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="product", fetch="EXTRA_LAZY")
     */
    private $photos;

    /**
     * Constructs product.
     */
    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    /**
     * Returns id.
     *
     * @return int|null Id.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns price.
     *
     * @return int|null Price.
     */
    public function getPrice(): ?int
    {
        if (!isset($this->price)) {
            return null;
        }
        $tmp = explode('.', $this->price);
        $out = intval($tmp[0]) * 100;
        if (count($tmp) > 1) {
            $out += intval($tmp[1]);
        }
        return $out;
    }

    /**
     * Changes price.
     *
     * @param int $price New price.
     * @return Product
     */
    public function setPrice(int $price): self
    {
        $this->price = intdiv($price,100) . '.' . ($price % 100);

        return $this;
    }

    /**
     * Returns colour.
     *
     * @return Colour|null Colour.
     */
    public function getColour(): ?Colour
    {
        return $this->colour;
    }

    /**
     * Changes colour.
     *
     * @param Colour|null $colour New colour.
     * @return Product
     */
    public function setColour(?Colour $colour): self
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * Returns type.
     *
     * @return Type|null Type.
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * Changes type.
     *
     * @param Type|null $type New type.
     * @return Product
     */
    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Returns photos.
     *
     * @return Collection|Photo[] Collection of photos.
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * Adds photo.
     *
     * @param Photo $photo Photo to add.
     * @return Product
     */
    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }

        return $this;
    }

    /**
     * Removes photo.
     *
     * @param Photo $photo Photo to remove.
     * @return Product
     */
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
