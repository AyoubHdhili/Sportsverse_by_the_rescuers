<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/client/home/{id}', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',

        ]);
    }
    #[Route('/client/banned', name: 'app_clientBanned')]
    public function client_banned(): Response
    {
        return $this->render('home/banned.html.twig');
    }
    #[Route('/coach/banned', name: 'app_coachBanned')]
    public function coach_banned(): Response
    {
        return $this->render('home/banned.html.twig');
    }


    #[Route('/coach/homeCoach/{id}', name: 'app_home_coach')]
    public function indexCoach(): Response
    {
        return $this->render('home/indexCoach.html.twig', [
            'controller_name' => 'HomeController',

        ]);
    }
}
