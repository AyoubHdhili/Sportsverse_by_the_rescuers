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
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Dompdf\Dompdf;
use Dompdf\Options;



#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    #[Route('/listr', name: 'app_reclamation_listr', methods: ['GET'])]
    public function listr(ReclamationRepository $reclamationRepository): Response
    {
        

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $l = $reclamationRepository->findAll();
        
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reclamation/lister.html.twig', [
            'reclamations' =>$l,
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
        return new Response();
    }
    #[Route('/lesreclamation/{page?1}/{nbre?3}', name: 'app_reclamation_index', methods: ['GET'])]
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
    public function create(Request $request, EntityManagerInterface $entityManager,NotifierInterface $notifier)
    {   $reclamation = new reclamation();
         $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
        $myDictionary = array(
            "tue", "merde", "pute",
            "gueule",
            "débile",
            "con",
            "abruti",
            "clochard",
            "sang"
        );
        dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myText = $request->get("reclamation")['description'];
            $badwords = new PhpBadWordsController();
            $badwords->setDictionaryFromArray($myDictionary)
                ->setText($myText);
            $check = $badwords->check();
            dump($check);
            if ($check){
            $notifier->send(new Notification('Mauvais mot ', ['browser']));} 
                else {

           
                $entityManager = $this->getdoctrine()->getManager();
                $entityManager->persist($reclamation);



                $entityManager->flush();
                $this->addFlash(
                    'info',
                    'Reclamation ajouté !!'
                );
            }

            return $this->redirectToRoute('app_reclamation_new', [], Response::HTTP_SEE_OTHER);
      
        }
    
        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),

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
