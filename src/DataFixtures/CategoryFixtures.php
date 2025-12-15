<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CAT_FUSIL = 'category-fusil';
    public const CAT_PISTOLET = 'category-pistolet';

    public function load(ObjectManager $manager): void
    {
        $cat1 = new Category();
        $cat1->setName('Fusils');
        $manager->persist($cat1);
        $this->addReference(self::CAT_FUSIL, $cat1);

        $cat2 = new Category();
        $cat2->setName('Pistolets');
        $manager->persist($cat2);
        $this->addReference(self::CAT_PISTOLET, $cat2);

        $manager->flush();
    }
}