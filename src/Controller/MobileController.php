<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Repository\ReponseRepository;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use PHPUnit\Util\Json;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;




class MobileController extends AbstractController
{
     /**
     * @Route("/allReclamations", name="app_mobile")
     */
    public function index(NormalizerInterface $normalizer): Response
    {
        $reclamations = $this->getDoctrine()->getManager()
            ->getRepository(Reclamation::class)
            ->findAll();

        $jsonContent = $normalizer->normalize($reclamations,'json',['groups'=>'reclamations']);

        return new JsonResponse($jsonContent);


    }



 

    /**
     * @Route("/detailReclamation", name="detail_mobile")
     */

    public function DetailReclamation(Request $request)
    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository(Reclamation::class)->find($id);
        $encoder= new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object->getDescription;
        });
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($reclamation);

        return new JsonResponse($formatted);


    }





     /**
     * @Route("/detailReponse", name="detail_mobile")
     */

    public function DetailReponse(Request $request)
    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $avis=$em->getRepository(Reponse::class)->find($id);
        $encoder= new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object->getDescription;
        });
        $serializer= new Serializer([new ObjectNormalizer()]);
        $formatted= $serializer->normalize($reponse);

        return new JsonResponse($formatted);


    }


       /**
     * @Route("/json/avis", name="listesAvis")
     */
    public function getReponse(EntityManagerInterface $em,NormalizerInterface $Normalizer)
    {
        $reponse=$em
        ->getRepository(Reponse::class)
        ->findAll();

        $jsonContent=$Normalizer->normalize($avis, 'json', ['groups'=>'aviss']);
        //dump($json);
        //die;
        return new Response(json_encode($jsonContent));
    
    }










     /**
     * @Route("/AddReponse", name="ajouteReponse")
     */
    public function add_reponse( NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request)
    {

        $reponse = new Reponse();


        
        $avis->setSujet($request->get('sujet'));
        $avis->setDescription($request->get('description'));

        $entityManager->persist($avis);
        $entityManager->flush();
        return new JsonResponse([
            'success' => "avis has been added"
        ]);
    }

        /**
     * @Route("/AddReclamation", name="ajouteReclamation")
     */
    public function add_reclamation( NormalizerInterface $normalizable, EntityManagerInterface $entityManager, Request $request)
    {

        $reclamation = new Reclamation();

        $daterec= $request->get("date");
        $reclamation->setNomClient($request->get("nomClient"));
        $reclamation->setSujet($request->get("sujet"));
        $reclamation->setidUser($request->get("idUser"));
        $reclamation->setEtat($request->get("etat"));
        $reclamation->setDescription($request->get("description"));
       // $reclamation->setDateReclamation($request->get("date_reclamation"));
       $reclamation->setDate(new \DateTime($daterec));
        
        

        $entityManager->persist($reclamation);
        $entityManager->flush();
        return new JsonResponse([
            'success' => "reclamation has been added"
        ]);
    }


    

    /**
     * @Route("/removeReponse/{id}",name="removeReponse")
     */

    public function removeReponse(EntityManagerInterface $em,$id):Response
    {

        $cat=$em
        ->getRepository(Reponse)::class
        ->find($id);
        $this->getDoctrine()->getManager()->remove($cat);

        $this->getDoctrine()->getManager()->flush();
        return $this->json(array('title'=>'successful','message'=> "Reponse supprimé avec succès"),200);

    }


    
    /**
     * @Route("/deleteJson/",name="deleteJson")
     */

    public function deleteReclamation(EntityManagerInterface $em,Request $request,NormalizerInterface $normalizer):Response
    {
        $id=$request->query->get("id");
        $reclamation= $em
            ->getRepository(Reclamation::class)
            ->find($id);
            //dd($reclamation);
        $em->remove($reclamation);
        $em->flush();
        $jsonContent = $normalizer->normalize($reclamation,'json',['groups'=>'reclamations']);
        return new Response(json_encode($jsonContent));

    }


    /**
     * @Route("/delReclamation/{id}", name="delreclamation")
     */


     public function delReclamationoffre(Request $request,NormalizerInterface $normalizer)
     {
         $em=$this->getDoctrine()->getManager();
         $rec=$this->getDoctrine()->getRepository(Reclamation::class)
             ->find($request->get("id"));
         $em->remove($rec);
         $em->flush();
         $jsonContent = $normalizer->normalize($rec,'json',['reclamation'=>'post:read']);
         return new Response(json_encode($jsonContent));}
   



    /**
     * @Route("/edit/reponse/{id}",name="editreponse")
     */

    public function editReponse(Request $request, EntityManagerInterface $em,$id):Reponse
    {
        $avis=$em
        ->getRepository(Avis::class)
        ->find($id);
       
        $avis->setDescription($request->get('description'));


        $this->getDoctrine()->getManager()->persist($airport);

        $this->getDoctrine()->getManager()->flush();
        return $this->json(array('title'=>'successful','message'=> "Reponse modifié avec succès"),200);

    }




     /**
     * @Route("/edit/reclamation/",name="editreclamation")
     */

    public function editReclamation(Request $request, EntityManagerInterface $em):Response
    {
        $reclamation = new Reclamation();
        $reclamation->setId($request->query->get("id"));
        
        $reclamation = $em
            ->getRepository(Reclamation::class)
            ->find($reclamation->getId());

        $reclamation->setNom($request->query->get("nom"));
        $reclamation->setPrenom($request->query->get("prenom"));
        $reclamation->setEmail($request->query->get("email"));
        $reclamation->setTel($request->query->get("tel"));
        $reclamation->setEtat($request->query->get("etat"));
        $reclamation->setDescription($request->query->get("description"));
        //$reclamation->setDateReclamation($request->query->get("date_reclamation"));

        //dd($reclamation);

        $this->getDoctrine()->getManager()->persist($reclamation);

        $this->getDoctrine()->getManager()->flush();
        return $this->json(array('title'=>'successful','message'=> "Reclamation modifié avec succès"),200);

    }



}