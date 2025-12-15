<?php

namespace App\Twig\Components;

use App\Repository\ProductRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ProductSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = ''; // Ce que l'utilisateur tape

    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProducts(): array
    {
        // Si la recherche est vide, on ne retourne rien (ou tout, au choix)
        if (empty($this->query)) {
            return []; // On laisse vide pour ne pas polluer l'écran, ou met findAll()
        }

        // On cherche dans le 'name' du produit.
        // Note: Tu devras peut-être adapter ta méthode findBy ou créer une méthode search()
        // Pour l'instant on fait simple avec le QueryBuilder dans le repo si besoin, 
        // ou une méthode native findBy(['name' => ...]) ne marche pas pour du "LIKE".
        
        return $this->productRepository->createQueryBuilder('p')
            ->where('p.name LIKE :query')
            ->setParameter('query', '%' . $this->query . '%')
            ->setMaxResults(5) // On limite à 5 résultats
            ->getQuery()
            ->getResult();
    }
}