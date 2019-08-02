<?php
/**
 * Change password helper.
 */

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Class ChangePassword
 *
 * @package App\Form\Model
 */
class ChangePassword
{
    /**
     * Old password.
     *
     * @SecurityAssert\UserPassword()
     */
    protected $oldPassword;

    /**
     * New password.
     *
     * @var string
     */
    protected $password;

    /**
     * Gets old password.
     *
     * @return mixed
     */
    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    /**
     * Sets old password.
     *
     * @param string $oldPassword New old password
     *
     * @return ChangePassword
     */
    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Gets password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Sets password.
     *
     * @param string $newPassword New password
     *
     * @return ChangePassword
     */
    public function setPassword(string $newPassword): self {
        $this->password = $newPassword;

        return $this;
    }
}
