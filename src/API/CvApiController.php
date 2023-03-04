<?php

namespace App\Controller\Api;

use App\Entity\Cv;
use App\Form\CvType;
use App\Repository\CvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/api/cv')]
class CvApiController extends AbstractController
{
    #[Route('/', name: 'api_cv_index', methods: ['GET'])]
    public function index(CvRepository $cvRepository, NormalizerInterface $normalizer): JsonResponse
    {
        $cvs = $cvRepository->findAll();

        $response = $normalizer->normalize($cvs, 'json', ['groups' => 'reclamations']);

        return $this->json($response);
    }

    #[Route('/{id}', name: 'api_cv_show', methods: ['GET'])]
    public function show(Cv $cv): JsonResponse
    {
        return $this->json([
            'id' => $cv->getId(),
            'activites' => $cv->getActivites(),
            'email' => $cv->getCertification(),
            'description' => $cv->getDescription(),
            'duree_experience' => $cv->getduree_experience(),
            'image' => $cv->getLevel(),
            'image' => $cv->getTarif()
        ]);
    }

    #[Route('/add', name: 'api_cv_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $cv = new Cv();
        $form = $this->createForm(CvType::class, $cv);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $entityManager->persist($cv);
            $entityManager->flush();

            return $this->json([
                'id' => $cv->getId(),
                'activites' => $cv->getActivites(),
                'email' => $cv->getCertification(),
                'description' => $cv->getDescription(),
                'duree_experience' => $cv->getduree_experience(),
                'image' => $cv->getLevel(),
                'image' => $cv->getTarif()
            ]);
        }

        return $this->json(Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'api_cv_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager, Cv $cv): JsonResponse
    {
        $form = $this->createForm(CvType::class, $cv);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $entityManager->flush();

            return $this->json([
                'id' => $cv->getId(),
                'activites' => $cv->getActivites(),
                'email' => $cv->getCertification(),
                'description' => $cv->getDescription(),
                'duree_experience' => $cv->getduree_experience(),
                'image' => $cv->getLevel(),
                'image' => $cv->getTarif()
            ]);
        }

        return $this->json(Response::HTTP_BAD_REQUEST);
    }

    #[Route('/{id}', name: 'api_cv_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, Cv $cv): JsonResponse
    {
        $entityManager->remove($cv);
        $entityManager->flush();

        return new JsonResponse('CV deleted successfully!', Response::HTTP_OK);
    }
}
