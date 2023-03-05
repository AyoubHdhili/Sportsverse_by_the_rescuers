<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgetType;
use App\Service\TwilioService;
use App\Form\ForgetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Egulias\EmailValidator\Result\InvalidEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/forgot-password", name="forgot_password")
     */
    public function index(Request $request, TwilioService $twilio, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $user = new User();
        //dd($request);
        $form = $this->createForm(ForgetPasswordType::class, $user);
        //dd($form);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // Get the email address of the user requesting the password reset

            $email = $form->get('email')->getData();

            // Find the user with the specified email address
            $user = $userRepository->findOneByEmail($email);

            if (!$user) {
                $this->addFlash('alert', 'InvalidEmail');
                return $this->redirectToRoute('forgot_password');
            } else {
                // Generate and save a unique password reset token for this user
                // Generate and save a unique rdom_bytes(32));
                $resetToken = bin2hex(random_bytes(3));
                //  dd($resetToken);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $resetToken

                    )
                );

                $message = 'Your password reset code is: ' . $resetToken;
                $twilio->sendSms($user->getNumTel(), $message);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Password reset SMS sent!');
                // Redirect the user back to the login page
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/forgot.html.twig', [
            'forgotForm' => $form->createView(),
        ]);
    }
}
