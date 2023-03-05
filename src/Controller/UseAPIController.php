<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UseAPIController extends AbstractController
{

    // #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    // public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->submit(json_decode($request->getContent(), true));

    //     if ($form->isValid()) {
    //         $user->setPassword(
    //             $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('password')->getData()
    //             )
    //         );
    //         $role = $form->get('rolle')->getData();
    //         $user->setRoles([$role]);
    //         $user->setIsVerified(false);
    //         $user->setIsBanned(false);
    //         $entityManager->persist($user);
    //         $entityManager->flush();
    //         $data = ['success' => true];
    //         return $this->json($data);
    //     } else {
    //         $errors = [];
    //         $formErrors = $form->getErrors(true, false);
    //         foreach ($formErrors as $error) {
    //             $errors[] = $error->getMessage();
    //         }
    //         $data = ['success' => false, 'errors' => $errors];
    //         return $this->json($data, 400);
    //     }
    // }

    #[Route('/api/dashboard/users', name: 'api_app_users_admin')]
    public function showApii(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = $serializer->serialize($users, 'json', ['groups' => ['user']]);
        return new JsonResponse($data, 200, [], true);
    }

    // #[Route('/api/user/{id}', name: 'api_user_show', methods: ['GET'])]
    // public function showApi(User $user, SerializerInterface $serializer): JsonResponse
    // {
    //     $data = $serializer->serialize($user, 'json', ['groups' => 'user']);
    //     return new JsonResponse($data, 200, [], true);
    // }
    // #[Route('/api/userdd/{id}', name: 'api_user_show', methods: ['GET'])]
    // public function showAp(User $user, NormalizerInterface $normalizer): JsonResponse
    // {
    //     $data = $normalizer->normalize($user);
    //     return new JsonResponse($data, 200, [], true);
    // }
    #[Route('/api/users/{id}', name: 'api_user_show', methods: ['GET'])]
    public function showUser(UserRepository $userRepository, int $id, NormalizerInterface $normalizer): JsonResponse
    {
        $user = $userRepository->find($id);
        //dd($user);
        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $data = $normalizer->normalize($user, null, ['groups' => 'user']);
        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
    #[Route('/api/register', name: 'api_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, NormalizerInterface $normalizer): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Create new user object
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setRoles($request->get('role'));
        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setAdresse($request->get('adresse'));
        $user->setNumTel($request->get('num_tel'));
        $user->setIsVerified(false);
        $user->setIsBanned(false);

        // Encode passwordjygjyg
        //   $hashedPassword = $passwordEncoder->encodePassword($user->setPassword($request->get('password')));
        // $user->setPassword($hashedPassword);



        $entityManager->persist($user);
        $entityManager->flush();

        // Serialize and return user object
        $serializedUser = $normalizer->normalize($user, null, ['groups' => 'user']);
        $data = ['success' => true, 'user' => $serializedUser];
        return new JsonResponse($data, 201);
    }
}
