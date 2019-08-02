<?php
/**
 * User helper.
 */

namespace App\Form\Model;

/**
 * Class UserHelper
 *
 * @package App\Form\Model
 */
class UserHelper
{
    /**
     * Primary key.
     *
     * @var int
     */
    protected $id;

    /**
     * Login.
     *
     * @var string
     */
    protected $login;

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
     * Sets id.
     *
     * @param int|null $id
     *
     * @return UserHelper
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets login.
     *
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * Sets login.
     *
     * @param string|null $login
     *
     * @return UserHelper
     */
    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }
}
