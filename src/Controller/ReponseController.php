<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\User;
use App\Entity\Reclamation;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MailerService;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Email;

#[Route('/reponse')]
class ReponseController extends AbstractController
{
    #[Route('/mail/{idreclamation}', name: 'app_mail')]
    public function index4( EntityManagerInterface $entityManager,UserRepository $UserRepository,$idreclamation): Response 
    {  
       
        $reclamation = $entityManager->getRepository(Reclamation::class)->find($idreclamation);
        $reclamation->setEtat("Traité");
         $entityManager->flush();

        
     return $this->render('reponse/index3.html.twig');
       
    }
    #[Route('/repondre', name: 'app_reclamation_repondre')]
    public function index5( EntityManagerInterface $entityManager,Request $request,MailerService $mailer,ReponseRepository $reponseRepository
    ): Response 
    { $email=   $request->get('Email');
        $message= $request->get('description');
        $mailer->sendEmail($email,$message);
        return $this->render('reponse/index.html.twig',[
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_reponse_index', methods: ['GET','POST'])]
    public function index(ReponseRepository $ReponseRepository,EntityManagerInterface $entityManager,Request $request    ): Response
    {
       
        $reponses = $entityManager
            ->getRepository(reponse::class)
            ->findAll();
        $back = null;
        if($request->isMethod("POST")){
            if ( $request->request->get('optionsRadios')){
                $SortKey = $request->request->get('optionsRadios');
                switch ($SortKey){
                    case 'sujet':
                        $reponses = $ReponseRepository->SortBysujet();
                        break;

                    case 'reponse':
                        $reponses = $ReponseRepository->SortByreponse();
                        break;

                

                }
            }
            else
            {
                $type = $request->request->get('optionsearch');
                $value = $request->request->get('Search');
                switch ($type){
                    case 'sujet':
                        $reponses = $ReponseRepository->findBysujet($value);
                        break;

               

                    case 'reponse':
                        $reponses = $ReponseRepository->findByreponse($value);
                        break;


                }
            }

            if ( $reponses ){
                $back = "success";
            }else{
                $back = "failure";
            }
        }
        return $this->render('reponse/index.html.twig', [
            'reponses'=>$reponses,
            'back' => $back,
        ]);
    }
    
 
   
    #[Route('{id}/new', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReponseRepository $reponseRepository ,EntityManagerInterface $entityManager ,Reclamation $id): Response
    {
        $reponse = new Reponse();
        $reponse->setIdReclamation($id);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponseRepository->save($reponse, true);
            $entityManager->persist($reponse);
            $entityManager->flush();
            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('reponse/new.html.twig', [
           // 'reponse' => $reponse,
            'form' => $form,
        ]);
    }
   
   

    #[Route('/lesReclamations', name: 'app_reclamation1_index', methods: ['GET'])]
    public function index1(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('reclamation/index1.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }
   
    

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponseRepository->save($reponse, true);

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $reponseRepository->remove($reponse, true);
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }
   
}
