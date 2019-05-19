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
        $count = 10;
        // TODO: needs object
        // $this->addReference('Category_count', $count);

        $this->createMany($count, 'categories', function ($i) {
            $category = new Category();
            $category->setName($this->faker->word);

            $this->addReference(Category::class.'_'.$i, $category);

            return $category;
        });

        $manager->flush();
    }
}
