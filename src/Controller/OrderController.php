<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\OrderStatus; // Vérifie que c'est le bon namespace de ton Enum
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order/create', name: 'app_order_validate')] // On appelle la route 'app_order_validate'
    public function validate(CartService $cartService, EntityManagerInterface $entityManager): Response
    {
        // 1. Vérifier si l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour passer commande.');
            return $this->redirectToRoute('app_login');
        }

        // 2. Vérifier si le panier n'est pas vide
        $cartItems = $cartService->getFullCart();
        if (empty($cartItems)) {
            return $this->redirectToRoute('cart_index');
        }

        // 3. Création de la Commande (Le conteneur)
        $order = new Order();
        $order->setUser($user);
        $order->setReference(uniqid('CMD-')); // Génère une ref unique ex: CMD-654a8f...
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus(OrderStatus::PENDING); // Adapte selon ton Enum (ex: 'En préparation')

        // 4. Boucle sur les produits du panier
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            // Protection Stock (Optionnel mais recommandé)
            if ($product->getStock() < $quantity) {
                $this->addFlash('danger', "Désolé, le produit " . $product->getName() . " n'a plus assez de stock.");
                return $this->redirectToRoute('cart_index');
            }

            // Création de la ligne de commande
            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order); // Ou setOrder($order) selon ton entité
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setPrice($product->getPrice()); // On fige le prix au moment de l'achat

            // Calcul du total
            $totalPrice += ($product->getPrice() * $quantity);

            // Gestion du Stock (Décrémentation)
            $product->setStock($product->getStock() - $quantity);

            // On persiste la ligne
            $entityManager->persist($orderItem);
        }

        // 5. Finalisation de la commande
        $order->setTotalPrice($totalPrice);
        $entityManager->persist($order);
        
        // On sauvegarde TOUT en base de données
        $entityManager->flush();

        // 6. Nettoyage
        $cartService->removeAll();

        // 7. Feedback
        $this->addFlash('success', 'Votre commande a été validée avec succès !');

        // Redirection vers une page de succès ou l'historique (qu'on fera plus tard)
        return $this->redirectToRoute('app_product_index'); 
    }
}