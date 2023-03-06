<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Entity\User;
use App\Form\SeanceType;
use App\Repository\EmplacementRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/seance')]
class SeanceController extends AbstractController
{
    private $security;
    private $session;
    public function __construct(Security $security, SessionInterface $session)
    {
        $this->security=$security;
        $this->session=$session;
    }
    #[Route('/', name: 'app_seance')]
    public function index(ManagerRegistry $doctrine,UserRepository $userRepository,EmplacementRepository $emplacementRepository): Response
    {
        $user=$this->security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('app_login');
        }else{
        $username=$user->getUsername();
        $this->session->set('username',$username);
        $seances=$doctrine->getManager()->getRepository(Seance::class)->findSeanceByAdresse($username);
        }
        return $this->render('seance/index.html.twig', [
            'seances' => $seances,
        ]);
    }
    #[Route('/coach_seance', name: 'coach_seance')]
    public function CoachSeance(ManagerRegistry $doctrine,UserRepository $userRepository): Response
    {
        $user=$this->security->getUser();
        if(is_null($user)){
            return $this->redirectToRoute('app_login');
        }
        else{
        $username=$user->getUsername();
        $this->session->set('username',$username);
        $seances=$doctrine->getManager()->getRepository(Seance::class)->findSeanceByAdresse($username);}
        return $this->render('coach_seance/list.html.twig', [
            'seances' => $seances,
        ]);
    }
    #[Route('/add/{id}',name:'add_seance')]
    public function addSeance(Request $request, ManagerRegistry $doctrine,UserRepository $userRepository,$id): Response
    {
        $user=$this->security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute('app_login');
        }else{
        $username=$user->getUsername();
        $this->session->set('username',$username);
        $seance=new Seance();
        $form=$this->createForm(SeanceType::class,$seance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $seance->setAdresse_Client($username);
            $seance->setCoach_Id($userRepository->find($id));
            $em=$doctrine->getManager();
            $em->persist($seance);
            $em->flush();
            return $this->redirectToRoute('app_seance');
        }}
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
            $seance->setEtat(NULL);
            $em=$doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_seance');
        }
        return $this->render('seance/update.html.twig',['formS' => $form->createView(),]);
    }
    #[Route('/show/{id}',name:'detail_seance')]
    public function detailSeance(Request $request,ManagerRegistry $doctrine,$id){
        $seance=$doctrine->getManager()->getRepository(Seance::class)->find($id);
        $ad=$seance->getAdresse_Client();
        $client=$doctrine->getManager()->getRepository(User::class)->findUserByEmail($seance->getAdresse_Client());
        return $this->render('seance/detail.html.twig',['seance'=>$seance,'client'=>$client]);
    }
}
