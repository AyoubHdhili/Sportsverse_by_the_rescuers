<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;        
    }
    #[Route('/registration', name: 'app_registration')]
    public function index(Request $request,ManagerRegistry $doctrine): Response
    {
        $user=new User();
        $form=$this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword($this->passwordEncoder->encodePassword($user,$user->getPassword()));
            $user->setRoles(['ROLE_USER']);
        
        $em=$doctrine->getManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('app_login'); 
        }
        return $this->render('registration/index.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}