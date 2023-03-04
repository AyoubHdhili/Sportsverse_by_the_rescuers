<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('front.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/profile/{id}', name: 'app_profile')]
    public function prodile(ManagerRegistry $doctrine, $id): Response
    {
        $repository = $doctrine->getRepository(Cv::class);
        $cv = $repository->find($id);

        return $this->render('profile.html.twig', [
            'controller_name' => 'HomeController',
            'cv' => $cv,
        ]);
    }
}
