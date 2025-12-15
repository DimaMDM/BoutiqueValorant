<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductStatus;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROD_VANDAL = 'product-vandal';

    public function load(ObjectManager $manager): void
    {
        // Produit 1
        $product = new Product();
        $product->setName('Vandal Prime');
        $product->setPrice(210000);
        $product->setDescription('Le classique doré.');
        $product->setStock(10);
        $product->setStatus(ProductStatus::AVAILABLE);
        
        // On lie à la catégorie Fusil
        $product->setCategory($this->getReference(CategoryFixtures::CAT_FUSIL));
        
        // Ajout d'une image (Bonus)
        $image = new Image();
        $image->setUrl('https://valorant-shop-assets.com/vandal-prime.png');
        $product->addImage($image);

        $manager->persist($product);
        $this->addReference(self::PROD_VANDAL, $product);

        // Produit 2
        $product2 = new Product();
        $product2->setName('Sheriff Ion');
        $product2->setPrice(177500);
        $product2->setDescription('Futuriste.');
        $product2->setStock(5);
        $product2->setStatus(ProductStatus::AVAILABLE);
        $product2->setCategory($this->getReference(CategoryFixtures::CAT_PISTOLET));
        
        $manager->persist($product2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}