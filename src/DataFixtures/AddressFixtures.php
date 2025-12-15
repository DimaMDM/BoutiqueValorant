<?php

namespace App\DataFixtures;

use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface; // <--- L'interface magique
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $address = new Address();
        $address->setStreet('10 rue de Bind');
        $address->setCity('Rabat');
        $address->setPostalCode('10000');
        $address->setCountry('Maroc');
        
        // On récupère le client créé à l'étape 1 grâce à l'étiquette
        $address->setUser($this->getReference(UserFixtures::CLIENT_USER_REFERENCE));
        
        $manager->persist($address);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class, // <--- Je dépends de UserFixtures
        ];
    }
}