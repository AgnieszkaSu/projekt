<?php
/**
 * Colour fixture.
 */
namespace App\DataFixtures;

use App\Entity\Colour;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ColourFixtures.
 */
class ColourFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'colours', function ($i) {
            $colour = new Colour();
            $colour->setName($this->faker->colorName);

            return $colour;
        });

        $manager->flush();
    }
}
