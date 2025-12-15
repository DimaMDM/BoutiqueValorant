<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LanguageController extends AbstractController
{
    #[Route('/language/{locale}', name: 'app_language_switch')]
    public function switchLanguage(Request $request, string $locale): Response
    {
        // On stocke la locale dans la session
        $request->getSession()->set('_locale', $locale);

        // On redirige vers la page d'accueil (ou la page précédente si on veut)
        return $this->redirectToRoute('app_home');
    }
}
