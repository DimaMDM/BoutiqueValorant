<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Création d'une commande pour le client
        $order = new Order();
        $order->setReference(uniqid('ORD-'));
        $order->setStatus(OrderStatus::PENDING);
        $order->setCreatedAt(new \DateTime());
        
        // On lie au client
        $order->setUser($this->getReference(UserFixtures::CLIENT_USER_REFERENCE));

        // On ajoute un produit à la commande (OrderItem)
        $item = new OrderItem();
        $item->setQuantity(1);
        
        // On récupère le produit Vandal créé avant
        $product = $this->getReference(ProductFixtures::PROD_VANDAL);
        
        $item->setProduct($product);
        $item->setProductPrice($product->getPrice()); // On fige le prix
        $item->setOrderRef($order); // On lie à la commande

        $manager->persist($item);
        $manager->persist($order);
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}