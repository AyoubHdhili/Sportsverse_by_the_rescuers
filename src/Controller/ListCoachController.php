<?php

namespace App\Controller;
use App\Entity\Emplacement;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListCoachController extends AbstractController
{
    #[Route('/list/coach', name: 'app_list_coach')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $coachs=$doctrine->getManager()->getRepository(User::class)->findCoachs();
        return $this->render('list_coach/index.html.twig', [
            'coachs' => $coachs,
        ]);
    }
}
