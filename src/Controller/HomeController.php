<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepository): Response
    {
        // Récupère tous les produits
        $allProducts = $productRepository->findAll();
        
        // Sélectionne 4 produits aléatoires
        $featuredProducts = [];
        if (count($allProducts) > 0) {
            $randomKeys = array_rand($allProducts, min(4, count($allProducts)));
            
            // Si un seul produit, array_rand retourne un entier, pas un tableau
            if (!is_array($randomKeys)) {
                $randomKeys = [$randomKeys];
            }
            
            foreach ($randomKeys as $key) {
                $featuredProducts[] = $allProducts[$key];
            }
        }
        
        return $this->render('home/index.html.twig', [
            'featured_products' => $featuredProducts,
        ]);
    }

    #[Route('/change-locale/{locale}', name: 'change_locale')]
    public function changeLocale($locale, \Symfony\Component\HttpFoundation\Request $request): Response
    {
        // On stocke la langue demandée dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}