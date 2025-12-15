<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Entity\OrderStatus;
use App\Entity\ProductStatus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminDashboardController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function index(OrderRepository $orderRepository, ProductRepository $productRepository): Response
    {
        // 1. Les 5 dernières commandes
        $lastOrders = $orderRepository->findBy([], ['createdAt' => 'DESC'], 5);

        // 2. Statistiques sur les produits
        $products = $productRepository->findAll();
        $statsCategory = [];
        // Initialisation dynamique basée sur l'Enum ProductStatus
        $statsStatus = [];
        foreach (ProductStatus::cases() as $status) {
            $statsStatus[$status->value] = 0;
        }

        foreach ($products as $product) {
            // Stats par Catégorie
            $catName = $product->getCategory() ? $product->getCategory()->getName() : 'Non classé';
            if (!isset($statsCategory[$catName])) {
                $statsCategory[$catName] = 0;
            }
            $statsCategory[$catName]++;

            // Stats par Disponibilité (basé sur l'Enum)
            if ($product->getStatus()) {
                $statsStatus[$product->getStatus()->value]++;
            }
        }

        // 3. Montant total des ventes (commandes livrées)
        $deliveredOrders = $orderRepository->findBy(['status' => OrderStatus::DELIVERED]);
        $totalSales = 0;
        
        foreach ($deliveredOrders as $order) {
            $totalSales += $order->getTotalPrice();
        }

        return $this->render('admin_dashboard/index.html.twig', [
            'lastOrders' => $lastOrders,
            'statsCategory' => $statsCategory,
            'statsStatus' => $statsStatus,
            'totalSales' => $totalSales / 100, // Conversion en euros
        ]);
    }
}
