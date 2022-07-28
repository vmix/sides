<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VariantController extends AbstractController
{
    #[Route('/variant', name: 'app_variant')]
    public function index(): Response
    {
        return $this->render('variant/index.html.twig', [
            'controller_name' => 'VariantController',
        ]);
    }
}
