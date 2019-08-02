<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures implements FixtureGroupInterface
{
    /**
     * Password encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        {
            $role = new Role();
            $role->setName('user');
            $manager->persist($role);
            $this->setReference('role_user', $role);
        }
        {
            $role = new Role();
            $role->setName('admin');
            $manager->persist($role);
            $this->setReference('role_admin', $role);
        }

        $this->createMany(10, 'users', function ($i) {
            $user = new User();
            $user->setLogin('user' . $i);
            $user->setRole($this->getReference('role_user'));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'user'.$i
            ));

            return $user;
        });

        $this->createMany(3, 'admins', function ($i) {
            $user = new User();
            $user->setLogin('admin' . $i);
            $user->setRole($this->getReference('role_admin'));
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin'.$i
            ));

            return $user;
        });

        $manager->flush();
    }

    /**
     * Gets groups.
     *
     * @return array
     */
    public static function getGroups(): array
    {
        return ['prod'];
    }
}
