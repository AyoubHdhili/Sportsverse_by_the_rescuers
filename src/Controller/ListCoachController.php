<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Cv;
use App\Entity\Emplacement;
use App\Entity\User;
use App\Form\SearchCoachType;
use App\Repository\CvRepository;
use App\Repository\UserRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListCoachController extends AbstractController
{
    #[Route('/list/coach', name: 'app_list_coach')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {

        $users = $doctrine->getManager()->getRepository(User::class)->findAll();
        $coachs = [];
        // $data = new SearchData();
        // $form = $this->createForm(SearchCoachType::class, $data);
        // $form->handleRequest($request);
        for ($i = 0; $i < count($users); $i++) {
            if ($users[$i]->getRoles()[0] == 'ROLE_COACH') {
                $coachs[$i] = $users[$i];
            }
        }
        return $this->render('list_coach/index.html.twig', [
            'coachs' => $coachs,
            // 'form' => $form->createView()
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
