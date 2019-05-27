<?php
/**
 * Product fixture.
 */
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProductFixtures.
 */
class ProductFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(90, 'products', function ($i) {
            $product = new Product();
            $product->setPrice($this->faker->randomFloat(2, 1, 100));
            $product->setType($this->getRandomReference('types'));
            $product->setColour($this->getRandomReference('colours'));

            return $product;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TypeFixtures::class,
            ColourFixtures::class
        );
    }
}
