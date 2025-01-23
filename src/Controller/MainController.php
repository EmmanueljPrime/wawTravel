<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_landing')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('landing.html.twig');
    }
}
