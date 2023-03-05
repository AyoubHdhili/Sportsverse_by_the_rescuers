<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReponseRepository;
use Doctrine\Persistence\ManagerRegistry;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\BarChart;



#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/reclamation/{page}/{nbre}', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository,EntityManagerInterface $entityManager,$page,$nbre,ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Reclamation::class);
        $nbReclamation = $repository->count([]);
        // 24
        $nbrePage = ceil($nbReclamation / $nbre) ;

        $reclamation= $repository->findBy([], [],$nbre, (intval($page) - 1 ) * $nbre);

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamation,
            'isPaginated' => true,
            'nbrePage' => $nbrePage,
            'page' => $page,
            'nbre' => $nbre
        ]);

        
    }

    #[Route('/statisreclamation', name: 'app_reclamation_statisreclamation', methods: ['GET'])]
public function statisreclamation(ReclamationRepository $ReclamationRepository)
{
    //on va chercher les categories
    $rech = $ReclamationRepository->barDep();
    $arr = $ReclamationRepository->barArr();
    
    $bar = new barChart ();
    $bar->getData()->setArrayToDataTable(
        [['reclamation', 'etat'],
         ['en cours', intVal($rech)],
         ['traite', intVal($arr)],
        

        ]
    );

    $bar->getOptions()->setTitle('les Reclamations');
    $bar->getOptions()->getHAxis()->setTitle('Nombre de reclamation');
    $bar->getOptions()->getHAxis()->setMinValue(0);
    $bar->getOptions()->getVAxis()->setTitle('etat');
    $bar->getOptions()->SetWidth(800);
    $bar->getOptions()->SetHeight(400);


    return $this->render('reclamation/statisreclamation.html.twig', array('bar'=> $bar )); 

}

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository,EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);
           //$entityManager->persist($reclamation);
           // $entityManager->flush();


            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
    #[Route('/Reponse', name: 'app_reponse_index1', methods: ['GET'])]
    public function index1(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index1.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }
    

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    { 
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
