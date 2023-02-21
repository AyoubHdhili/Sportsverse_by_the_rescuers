<?php

namespace App\Controller;

use App\Entity\Seance;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoachSeanceController extends AbstractController
{
    #[Route('/coach/seance', name: 'app_coach_seance')]
    public function index(): Response
    {
        return $this->render('coach_seance/index.html.twig', [
        ]);
    }
    #[Route('/accepter/{id}',name:'accepter_seance')]
    public function AccepterSeance(Request $request,ManagerRegistry $doctrine,$id):Response{
        $seance=$doctrine->getManager()->getRepository(Seance::class)->find($id);
        $seance->setEtat('Acceptée');
        $em=$doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('coach_seance');
    }
    #[Route('/refuser/{id}',name:'refuser_seance')]
    public function RefuserSeance(Request $request,ManagerRegistry $doctrine,$id):Response{
        $seance=$doctrine->getManager()->getRepository(Seance::class)->find($id);
        $seance->setEtat('Refusée');
        $em=$doctrine->getManager();
        $em->flush();
        return $this->redirectToRoute('coach_seance');
    }
}
