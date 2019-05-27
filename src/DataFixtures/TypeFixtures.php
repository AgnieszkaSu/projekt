<?php
/**
 * Type fixture.
 */
namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TypeFixtures.
 */
class TypeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(30, 'types', function ($i) {
            $type = new Type();
            $type->setName($this->faker->word);
            $type->setDescription($this->faker->sentence);
            $type->setCategory($this->getRandomReference('categories'));

            return $type;
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
