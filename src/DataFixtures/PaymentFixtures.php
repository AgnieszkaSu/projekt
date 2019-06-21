<?php
/**
 * Payment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\PaymentMethod;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PaymentFixtures.
 */
class PaymentFixtures extends AbstractBaseFixtures
{
    /**
     * Load.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        {
            $payment = new PaymentMethod();
            $payment->setType('Bank trasfer');
            $manager->persist($payment);
        }
        {
            $payment = new PaymentMethod();
            $payment->setType('Cash');
            $manager->persist($payment);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}
