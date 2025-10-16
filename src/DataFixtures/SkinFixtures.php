<?php

namespace App\DataFixtures;

use App\Entity\Skin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SkinFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $skin = new Skin();
            $skin->setName('Skin ' . $i);
            $skin->setPrice(rand(10, 100));
            $skin->setImage('skin' . $i . '.png');
            $skin->setDescription('This is the description for skin ' . $i);
            $manager->persist($skin);
        }

        $manager->flush();
    }
}
