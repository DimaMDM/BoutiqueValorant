<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    // On crée une constante pour rappeler cette référence ailleurs
    public const ADMIN_USER_REFERENCE = 'user-admin';
    public const CLIENT_USER_REFERENCE = 'user-client';

    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Admin
        $admin = new User();
        $admin->setEmail('admin@valorant.shop');
        $admin->setFirstName('Jett');
        $admin->setLastName('Wind');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        $manager->persist($admin);
        
        // On pose l'étiquette sur l'admin
        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);

        // Client
        $client = new User();
        $client->setEmail('client@valorant.shop');
        $client->setFirstName('Phoenix');
        $client->setLastName('Fire');
        $client->setRoles(['ROLE_USER']);
        $client->setPassword($this->passwordHasher->hashPassword($client, 'password'));
        $manager->persist($client);

        // On pose l'étiquette sur le client
        $this->addReference(self::CLIENT_USER_REFERENCE, $client);

        $manager->flush();
    }
}