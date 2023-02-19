<?php

namespace App\Controller;

use App\Entity\Emplacement;
use App\Entity\Seance;
use App\Form\EmplacementType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/emplacement')]
class EmplacementController extends AbstractController
{
    #[Route('/', name: 'app_emplacement')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $emplacements=$doctrine->getManager()->getRepository(Seance::class)->findAll();
        return $this->render('emplacement/index.html.twig', [
            'emplacements'=>$emplacements,
        ]);
    }
    #[Route('/add', name: 'add_emplacement')]
    public function addEmplacement(Request $request, ManagerRegistry $doctrine): Response
    {
        $emplacement=new Emplacement();
        $form=$this->createForm(EmplacementType::class,$emplacement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist($emplacement);
            $em->flush();
            return $this->redirectToRoute('add_seance');
        }
        return $this->render('emplacement/add.html.twig',['formE'=>$form->createView(),]);
    }
    #[Route('/delete/{id}', name:'del_emplacement')]
    public function delEmplacement(Emplacement $emplacement,ManagerRegistry $doctrine):Response
    {
        $em=$doctrine->getManager();
        $em->remove($emplacement);
        $em->flush();
        return $this->redirectToRoute('app_emplacement');
    }
    #[Route('/update/{id}')]
    public function updateEmplacement(Request $request,ManagerRegistry $doctrine,$id):Response
    {
        $emplacement=$doctrine->getManager()->getRepository(Emplacement::class)->find($id);
        $form=$this->createForm(EmplacementType::class,$emplacement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_emplacement');
        }
        return $this->render('emplacement/update.html.twig',['formE'=>$form->createView()]);
    }
}
