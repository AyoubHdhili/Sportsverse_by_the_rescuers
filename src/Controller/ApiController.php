<?php

namespace App\Controller;

use App\Entity\Emplacement;
use App\Entity\Seance;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EmplacementChoixRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
    #[Route('/seance', name: 'app_api_seance')]
    public function listSeance(ManagerRegistry $doctrine): Response
    {
        $seance=$doctrine->getManager()->getRepository(Seance::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($seance);
        return new JsonResponse($formatted);
    }
    #[Route('/seance/ajout',name:'api_ajout_seance')]
    public function addSeance(Request $request, ManagerRegistry $doctrine){
        $seance=new Seance();
        $date=$request->query->get("date");
        $etat=$request->query->get("etat");
        $duree=$request->query->get("duree");
        $coach_id=$request->query->get("coach_id");
        $adresse_client=$request->query->get("adresse_client");
        $emplacement=$request->query->get("emplacement");
        $message=$request->query->get("message");
        $em=$doctrine->getManager();
        //$seance->setDate($date);
        $seance->setEtat($etat);
        $seance->setDuree($date);
        $seance->setCoach_Id($coach_id);
        $seance->setAdresse_Client($adresse_client);
        $seance->setEmplacement($emplacement);
        $seance->setMessage($message);
        $em->persist($seance);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($seance);
        return new JsonResponse($formatted);
    }
    #[Route('/seance/delete',name:'api_delete_seance')]
    public function delSeance(Request $request,ManagerRegistry $doctrine){
        $id=$request->get("id");
        $em=$doctrine->getManager();
        $seance=$em->getRepository(Seance::class)->find($id);
        if($seance != null){
            $em->remove($seance);
            $em->flush();
            $serializer=new Serializer([new ObjectNormalizer()]);
            $formatted=$serializer->normalize("La séance a été supprimé avec succés");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id seance non valide");
    }
    #[Route('/seance/update',name:'api_update_seance')]
    public function updateSeance(Request $request, ManagerRegistry $doctrine){
        $em=$doctrine->getManager();
        $seance=$em->getRepository(Seance::class)->find($request->get("id"));
        //$seance->setDate($date);
        $seance->setEtat($request->get("etat"));
        $seance->setDuree($request->get("duree"));
        $seance->setCoach_Id($request->get("coach_id"));
        $seance->setAdresse_Client($request->get("adresse_client"));
        $seance->setEmplacement($request->get("emplacement"));
        $seance->setMessage($request->get("message"));
        $em->persist($seance);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($seance);
        return new JsonResponse("Seance modifié avec succés");
    }
    #[Route('/emplacement/ajout',name:'api_ajout_emplacement')]
    public function addEmplacement(Request $request, ManagerRegistry $doctrine): Response
    {
        $emplacement=new Emplacement();
        $governorat=$request->query->get("governorat");
        $delegation=$request->query->get("delegation");
        $type=$request->query->get("type");
        $adresse=$request->query->get("adresse");
        $localite=$request->query->get("localite");
        $em=$doctrine->getManager();
        $emplacement->setGovernorat($governorat);
        $emplacement->setDelegation($delegation);
        $emplacement->setType($type);
        $emplacement->setAdresse($adresse);
        $emplacement->setLocalite($localite);
        $em->persist($emplacement);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($emplacement);
        return new JsonResponse($formatted);
    }
    #[Route('/emplacement/delete',name:'api_delete_emplacement')]
    public function delEmplacement(Request $request, ManagerRegistry $doctrine){
        $id=$request->get("id");
        $em=$doctrine->getManager();
        $emplacement=$em->getRepository(Emplacement::class)->find($id);
        if($emplacement != null){
            $em->remove($emplacement);
            $em->flush();
            $serializer=new Serializer([new ObjectNormalizer()]);
            $formatted=$serializer->normalize("L'emplacement a été supprimé avec succés");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id emplacement invalide");
    }
    #[Route('/emplacement/update',name:'api_update_emplacement')]
    public function updateEmplacement(Request $request, ManagerRegistry $doctrine){
        $em=$doctrine->getManager();
        $emplacement=$em->getRepository(Emplacement::class)->find($request->get("id"));
        $emplacement->setGovernorat($request->get("governorat"));
        $emplacement->setDelegation($request->get("delegation"));
        $emplacement->setType($request->get("type"));
        $emplacement->setAdresse($request->get("adresse"));
        $emplacement->setLocalite($request->get("localite"));
        $em->persist($emplacement);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($emplacement);
        return new JsonResponse("Emplacement modifié avec succés");
    }
    #[Route('/emplacement',name:'api_emplacement')]
    public function listEmplacement(ManagerRegistry $doctrine){
        $emplacements=$doctrine->getManager()->getRepository(Emplacement::class)->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($emplacements);
        return new JsonResponse($formatted);
    }
}
