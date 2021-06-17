<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
          1 => [
              'name' => 'Grabs'
          ],
          2 => [
              'name' => 'Rotations'
          ],
          3 => [
              'name' => 'Flips'
          ],
          4 => [
              'name' => 'Rotations désaxées'
          ],
          5 => [
              'name' => 'Slides'
          ],
          6 => [
              'name' => 'One foot'
          ],
          7 => [
              'name' => 'Old school'
          ],
        ];

        foreach ($categories as $key => $value){
            $category = new Category();
            $category->setName($value['name']);

            $manager->persist($category);

            $this->addReference('category_' . $key, $category);
        }

        $manager->flush();
    }
}
