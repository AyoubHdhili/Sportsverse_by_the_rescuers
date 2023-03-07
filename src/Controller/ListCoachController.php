<?php

namespace App\Controller;

use App\Entity\Emplacement;
use App\Entity\User;
use App\Repository\CvRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListCoachController extends AbstractController
{
    #[Route('/list/coach', name: 'app_list_coach')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getManager()->getRepository(User::class)->findAll();
        $coachs = [];
        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]->getRoles()[0] == 'ROLE_COACH') {
                $coachs[$i] = $users[$i];
            }
        }
        return $this->render('list_coach/index.html.twig', [
            'coachs' => $coachs,
        ]);
    }
    #[Route('/profile/coach/{id}', name: 'app_profile_coach')]
    public function profile(ManagerRegistry $doctrine, $id, UserRepository $userRepository, CvRepository $cvRepository): Response
    {
        // $cv = $doctrine->getManager()->getRepository(Cv::class)->findBy(['user_id' => $id]);
        $coach = $userRepository->find($id);
        $cv = $cvRepository->findBy(['user_id' => $id]);

        return $this->render('cv/detail.html.twig', [
            'coach' => $coach,
            'cv' => $cv
        ]);
    }
}
