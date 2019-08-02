<?php
/**
 * OrderProducts entity.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class OrderProducts.
 *
 * @ORM\Entity(repositoryClass="App\Repository\OrderProductsRepository")
 * @ORM\Table(name="zamowienia_produkty")
 */
class OrderProducts
{
    /**
     * Primary key.
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_zamowienia_produkty", type="integer")
     */
    private $id;

    /**
     * Quantity.
     *
     * @ORM\Column(name="ilosc", type="integer")
     */
    private $quantity;

    /**
     * Price.
     *
     * @ORM\Column(name="cena", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * Order.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderProducts")
     * @ORM\JoinColumn(name="zamowienie", referencedColumnName="id_zamowienia", nullable=false)
     */
    private $order;

    /**
     * Products.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(name="produkty_id_produktu", referencedColumnName="id_produktu", nullable=false)
     */
    private $product;

    /**
     * Gets id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets quantity.
     *
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * Sets quantity.
     *
     * @param int $quantity
     *
     * @return OrderProducts
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Gets price.
     *
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets price.
     *
     * @param $price
     *
     * @return OrderProducts
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets order.
     *
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * Sets order.
     *
     * @param Order|null $order
     *
     * @return OrderProducts
     */
    public function setOrder(?Order $order): self
    {
        $this->order = $order;

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
     * @return OrderProducts
     */
    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
