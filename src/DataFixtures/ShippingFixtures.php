<?php
/**
 * Shipping fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ShippingMethod;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ShippingFixtures.
 */
class ShippingFixtures extends AbstractBaseFixtures implements FixtureGroupInterface
{
    /**
     * Load.
     *
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        {
            $shipping = new ShippingMethod();
            $shipping->setType('Local pickup');
            $manager->persist($shipping);
        }
        {
            $shipping = new ShippingMethod();
            $shipping->setType('Royal Mail');
            $manager->persist($shipping);
        }
        {
            $shipping = new ShippingMethod();
            $shipping->setType('DHL');
            $manager->persist($shipping);
        }
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
