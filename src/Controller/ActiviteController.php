<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activite')]
class ActiviteController extends AbstractController
{
    #[Route('/', name: 'app_activite')]
    public function index(): Response
    {
        return $this->render('activite/index.html.twig', [
            'controller_name' => 'ActiviteController',
        ]);
    }

    #[Route('/list', name: 'list_activite')]
    public function list(ActiviteRepository $repository): Response
    {
        // $repository = $doctrine->getRepository(Activite::class);
        $activites = $repository->findAll();
        return $this->render('activite/list.html.twig', [
            'controller_name' => 'ActiviteController',
            'activites' => $activites,

        ]);
    }
    #[Route('/admin/list', name: 'admin_list_activite')]
    public function admin_list(ActiviteRepository $repository): Response
    {
        // $repository = $doctrine->getRepository(Activite::class);
        $activites = $repository->findAll();
        return $this->render('activite/admin/list.html.twig', [
            'controller_name' => 'ActiviteController',
            'activites' => $activites,
        ]);
    }
    #[Route('/show/{id}', name: 'show_activite')]
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Activite::class);
        $activite = $repository->find($id);

        return $this->render('activite/detail.html.twig', [
            'controller_name' => 'ActiviteController',
            'activite' => $activite,
        ]);
    }
    #[Route('/admin/show/{id}', name: 'admin_show_activite')]
    public function admin_show(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Activite::class);
        $activite = $repository->find($id);

        return $this->render('activite/detail.html.twig', [
            'controller_name' => 'ActiviteController',
            'activite' => $activite,
        ]);
    }

    #[Route('/add', name: 'add_activite')]
    public function add(Request $request, ManagerRegistry $doctrine): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $em->persist($activite);
            $em->flush();
            return $this->redirectToRoute('list_activite');
        }
        // return $this->render('activite/detail.html.twig', [
        //     'formC' => $form->createView()
        // ]);
        return $this->renderForm('activite/add.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }
    #[Route('/admin/add', name: 'admin_add_activite')]
    public function admin_add(Request $request, ManagerRegistry $doctrine): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $em->persist($activite);
            $em->flush();
            return $this->redirectToRoute('admin_list_activite');
        }
        // return $this->render('activite/detail.html.twig', [
        //     'formC' => $form->createView()
        // ]);
        return $this->renderForm('activite/admin/add.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }
    #[Route('/update/{id}', name: 'update_activite')]
    public function update(ManagerRegistry $doctrine, Request $request, $id)
    {
        $repository = $doctrine->getRepository(Activite::class);
        $activite = $repository->find($id);
        $form = $this->createForm(ActiviteType::class, $activite);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($activite);
            $em->flush();
            return $this->redirectToRoute('admin_list_activite');
        }

        return $this->renderForm('activite/admin/update.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/delete/{id}', name: 'delete_activite')]
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Activite::class);
        $activite = $repository->find($id);

        $em = $doctrine->getManager();
        $em->remove($activite);
        $em->flush();
        return $this->redirectToRoute("admin_list_activite");
    }
}
