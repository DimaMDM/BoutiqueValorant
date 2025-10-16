<?php

namespace App\Controller;

use App\Repository\SkinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SkinRepository $skinRepository): Response
    {
        // Récupère les 6 derniers skins ajoutés (ou tu peux créer une méthode pour les skins "featured")
        $featuredSkins = $skinRepository->findBy([], ['id' => 'DESC'], 6);
        
        return $this->render('home/index.html.twig', [
            'featured_skins' => $featuredSkins,
        ]);
    }
}