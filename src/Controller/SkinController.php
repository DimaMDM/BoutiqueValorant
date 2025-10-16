<?php

namespace App\Controller;

use App\Repository\SkinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SkinController extends AbstractController
{
    #[Route('/skins', name: 'app_skin_index')]
    public function index(SkinRepository $skinRepository): Response
    {
        $selectedSkins = $skinRepository->findRandomSkins(4);

        return $this->render('skin/index.html.twig', [
            'skins' => $selectedSkins,
        ]);
    }
}
