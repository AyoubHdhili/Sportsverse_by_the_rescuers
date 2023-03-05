<?php

namespace App\Controller;

use App\Entity\AppUser;
use App\Entity\User;
use App\Form\User\UserType;
use App\Form\User\LoginType;
use App\Form\User\UpdateType;
use App\Repository\AppUserRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/login.html.twig', [
            'controller_name' => 'UserController',

        ]);
    }

    #[Route('/disabled/{id}', name: 'app_user_banned')]
    public function banned_user($id, AppUserRepository $userRepository): Response
    {
        $user = $userRepository->findById($id);
        $user->isBanned(true);
        $userRepository->save($user);
        $users = $userRepository->findAll();
        //dd($users);
        return $this->render('user/show.html.twig', array(
            'users' => $users,
        ));
    }


    #[Route('/dashboard/users', name: 'app_users_admin')]
    public function Show(AppUserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        //dd($users);
        return $this->render('user/show.html.twig', array(
            'users' => $users,
        ));
    }

    #[Route('/users/update/{id}', name: 'app_users_update', methods: ['GET', 'POST'])]
    public function edit(Request $request, AppUser $user, AppUserRepository $userRepository): Response
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
    public function delete(Request $request, User $student, UserRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student, true);
        }

        return $this->redirectToRoute('app_users_admin', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_user_afficher', methods: ['GET'])]
    public function afficher(AppUser $user): Response
    {
        return $this->render('user/afficher.html.twig', [
            'user' => $user,
        ]);
    }
}
