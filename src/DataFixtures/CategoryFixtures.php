<?php
/**
 * Category fixture.
 */
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CategoryFixtures.
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(12, 'categories', function ($i) {
            $category = new Category();
            $category->setName($this->faker->word);

            return $category;
        });

        $manager->flush();
    }
}
