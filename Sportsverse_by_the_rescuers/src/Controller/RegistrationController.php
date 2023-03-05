<?php

namespace App\Controller;

use App\Entity\AppUser;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new AppUser();
        //dd($request);
        $form = $this->createForm(RegistrationFormType::class, $user);
        //dd($form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            // dd($form->get('password'));
            // dd($form->get('password'));
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $rolle = $form->get('rolle')->getData();

            $user->setRoles([$rolle]);
            $user->setIsVerified(false);
            $user->setIsBanned(false);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            //redirect to login
            //   $token = '';

            // Set the cookie's expiration date to a past date
            //   setcookie('token', $token, time() - 3600, '/');
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
