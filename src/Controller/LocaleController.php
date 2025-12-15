<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/change-locale/{locale}', name: 'change_locale')]
    public function changeLocale(string $locale, Request $request): Response
    {
        // 1. On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // 2. On revient à la page précédente (referer)
        return $this->redirect($request->headers->get('referer', $this->generateUrl('app_home')));
    }
}
