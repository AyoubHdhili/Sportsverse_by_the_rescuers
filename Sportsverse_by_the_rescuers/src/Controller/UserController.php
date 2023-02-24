<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User\UserType;
use App\Form\User\LoginType;
use App\Form\User\UpdateType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',
            
        ]);
    }

    #[Route('/signup', name: 'app_add_user', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/create.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
#[Route('/login', name: 'app_login')]
public function Login(Request $request,UserRepository $userRepository,ValidatorInterface $validator): Response
    {
        $user=new User();
        $Form=$this->createForm(LoginType::class,$user);
        $Form->handleRequest($request);

        $errors = $validator->validate($user);

        if(count($errors) > 0){
            return $this->render('user/login.html.twig', array(
                'form'=>$Form->createView(),
                'errors'=>$errors
            ));
        }

        if ($Form->isSubmitted()&&$Form->isValid())/*verifier */
        {
            $userInDb = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if(!$userInDb){
                return $this->render('user/login.html.twig', array(
                    'form'=>$Form->createView(),
                    'errors'=>array(),
                    'message'=>"Utilisateur non trouvé dans notre base de données"
                ));
            }

            if($userInDb->getPassword() != $user->getPassword()){
                return $this->render('user/login.html.twig', array(
                    'form'=>$Form->createView(),
                    'errors'=>array(),
                    'message'=>"Mot de passe incorrect"
                ));
            }
            if($userInDb->getRole() == "admin"){
                return $this->redirectToRoute('app_dashboard');
            }
            return $this->redirectToRoute('app_home');
        }

        return $this->render('user/login.html.twig', array(
            'form'=>$Form->createView(),
            'errors'=>array()
        ));
    }
    #[Route('/dashboard/users', name: 'app_users_admin')]
public function Show(UserRepository $userRepository): Response
{
    $users = $userRepository->findAll();
    return $this->render('user/show.html.twig', array(
        'users'=>$users,
    ));
}

#[Route('/users/update/{id}', name: 'app_users_update', methods: ['GET', 'POST'])]
public function edit(Request $request, User $user, UserRepository $userRepository): Response
{
    $form = $this->createForm(UpdateType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $userRepository->save($user, true);

        return $this->redirectToRoute('app_users_admin', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('user/update.html.twig', [
        'user' => $user,
        'form' => $form,
    ]);
}

// #[Route('/users/delete', name: 'app_users_delete')]
// public function delete(Request $request,UserRepository $userRepository,ManagerRegistry $doctrine): Response
// {
//     $id = $request->query->get('id', 'null');
//     if($id!='null'){
//         $em = $doctrine->getManager();
//         $user = $userRepository->find($id);
//         $em->remove($user);
//         $em->flush();
//         return $this->redirectToRoute('app_users_admin');
//     }
//     return $this->redirectToRoute('app_users_admin');
// }
//   #[Route('users/delete/{id}', name: 'app_users_delete')]
//     public function delete(Request $request, $id) {
//         $user= $this->getDoctrine()->getRepository(User::class)->find($id);

    
//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->remove($user);
//         $entityManager->flush();
//         $response = new Response();
//         $response->send();
//         return $this->redirectToRoute('app_users_admin');
//     }
    #[Route('/{id}', name: 'app_users_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getNSC(), $request->request->get('_token'))) {
            $studentRepository->remove($student, true);
        }

        return $this->redirectToRoute('app_users_admin', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_user_afficher', methods: ['GET'])]
       public function afficher(User $user): Response
    {
        return $this->render('user/afficher.html.twig', [
            'user' => $user,
        ]);
    }


}
