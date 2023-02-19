<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmplacementChoixController extends AbstractController
{
    #[Route('/emplacement/choix', name: 'app_emplacement_choix')]
    public function index(): Response
    {
        return $this->render('emplacement_choix/index.html.twig', [
            'controller_name' => 'EmplacementChoixController',
        ]);
    }
}
