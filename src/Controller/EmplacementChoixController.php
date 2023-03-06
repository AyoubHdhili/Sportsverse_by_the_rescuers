<?php

namespace App\Controller;

use App\Repository\EmplacementChoixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EmplacementChoixController extends AbstractController
{
    #[Route('/emplacement/choix', name: 'app_emplacement_choix')]
    public function index(): Response
    {
        return $this->render('emplacement_choix/index.html.twig', [
            'controller_name' => 'EmplacementChoixController',
        ]);
    }
    #[Route('/emplacement/choix/list',name:'emp_list')]
    public function all(EmplacementChoixRepository $rep, NormalizerInterface $normalizer){
        $emplacements=$rep->findAll();
        $emplacementnormalises=$normalizer->normalize($emplacements,'json');
        $json=json_encode($emplacementnormalises);
        return new Response($json);
    }
}
