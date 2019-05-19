<?php
/**
 * Type fixture.
 */
namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TypeFixtures.
 */
class TypeFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'types', function ($i) {
            $type = new Type();
            $type->setName($this->faker->word);
            $type->setDescription($this->faker->sentence);
            $type->setCategory($this->getReference(Category::class.'_0'));

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
