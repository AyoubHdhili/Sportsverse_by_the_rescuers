<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/seance')]
class SeanceController extends AbstractController
{
    #[Route('/', name: 'app_seance')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $seances=$doctrine->getManager()->getRepository(Seance::class)->findAll();
        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
        ]);
    }
    #[Route('/add',name:'add_seance')]
    public function addSeance(Request $request, ManagerRegistry $doctrine): Response
    {
        $seance=new Seance();
        $form=$this->createForm(SeanceType::class,$seance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist($seance);
            $em->flush();
            return $this->redirectToRoute('app_seance');
        }
        return $this->render('seance/add.html.twig',['formS' => $form->createView(),]);
    }
    #[Route('/delete/{id}',name:'del_seance')]
    public function delSeance(Seance $seance,ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->remove($seance);
        $em->flush();
        return $this->redirectToRoute('app_seance');
    }
    #[Route('/update/{id}',name:'update_seance')]
    public function updateSeance(Request $request, ManagerRegistry $doctrine,$id): Response
    {
        $seance=$doctrine->getManager()->getRepository(Seance::class)->find($id);
        $form=$this->createForm(SeanceType::class,$seance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_seance');
        }
        return $this->render('seance/update.html.twig',['formS' => $form->createView(),]);
    }
}
