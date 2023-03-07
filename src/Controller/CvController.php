<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Entity\User;

use App\Form\CvType;
use App\Repository\CvRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/cv')]
class CvController extends AbstractController
{
    private $security;
    private $session;
    public function __construct(Security $security, SessionInterface $session)
    {
        $this->security = $security;
        $this->session = $session;
    }
    #[Route('/', name: 'app_cv')]
    public function index(): Response
    {
        return $this->render('cv/index.html.twig', [
            'controller_name' => 'CvController',
        ]);
    }
    #[Route('/list', name: 'list_cv')]
    public function list(CvRepository $repository): Response
    {
        // $repository = $doctrine->getRepository(Cv::class);
        $cvs = $repository->findAll();
        return $this->render('cv/list.html.twig', [
            'controller_name' => 'CvController',
            'cvs' => $cvs,
        ]);
    }
    #[Route('/admin/list', name: 'admin_list_cv')]
    public function admin_list(CvRepository $repository): Response
    {
        // $repository = $doctrine->getRepository(Cv::class);
        $cvs = $repository->findAll();
        return $this->render('cv/admin/list.html.twig', [
            'controller_name' => 'CvController',
            'cvs' => $cvs,
        ]);
    }
    #[Route('/show/{id}', name: 'show_cv')]
    public function show(ManagerRegistry $doctrine, UserRepository $userRepository, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Cv::class);
        $coach = $userRepository->find($id);

        $cv = $repository->find($coach);

        return $this->render('cv/detail.html.twig', [
            'controller_name' => 'CvController',
            'cv' => $cv,
            'coach' => $coach
        ]);
    }
    #[Route('/add', name: 'add_cv')]
    public function add(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $user = $this->security->getUser();
        $cv = new Cv();

        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $cv->setImage($newFilename);
            }
            $cv->setUserId($user);
            $em = $doctrine->getManager();
            $em->persist($cv);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->renderForm('cv/add.html.twig', [
            'cv' => $cv,
            'form' => $form,
        ]);
    }
    #[Route('/admin/add', name: 'admin_add_cv')]
    public function admin_add(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $cv = new Cv();
        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('cv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $cv->setImage($newFilename);
            }

            $em = $doctrine->getManager();
            $em->persist($cv);
            $em->flush();
            return $this->redirectToRoute('admin_list_cv');
        }
        return $this->renderForm('cv/admin/add.html.twig', [
            'cv' => $cv,
            'form' => $form,
        ]);
    }
    #[Route('/update/{id}', name: 'update_cv')]
    public function update(ManagerRegistry $doctrine, Request $request, UserRepository $userRepository,  $id)
    {
        $repository = $doctrine->getRepository(Cv::class);
        $cv = $repository->find($id);

        $coach = $userRepository->find($id);
        $form = $this->createForm(CvType::class, $cv);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cv->setUserId($coach);
            $em = $doctrine->getManager();
            $em->persist($cv);
            $em->flush();
            return $this->redirectToRoute('list_cv');
        }

        return $this->renderForm('cv/update.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/delete/{id}', name: 'delete_cv')]
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        // declaring the repository in a variable
        $repository = $doctrine->getRepository(Cv::class);
        $cv = $repository->find($id);

        $em = $doctrine->getManager();
        $em->remove($cv);
        $em->flush();
        return $this->redirectToRoute("list_cv");
    }
    #[Route('/pdf/{id}',  name: 'cv_pdf')]
    public function pdfDownload(Cv $cv, ManagerRegistry $doctrine, UserRepository $userRepository, $id): Response
    {
        $repository = $doctrine->getRepository(Cv::class);
        $cv = $repository->find($id);
        $coach = $userRepository->find($id);

        $dompdf = new Dompdf();
        $pdfOptions = new Options();
        $pdfOptions->set(array('isRemoteEnabled' => true));
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->render('cv/cv_pdf.html.twig', [
            'coach' => $coach,
            'cv' => $cv,
        ]);
        // Generate the PDF
        $dompdf->loadHtml($html->getContent());
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        // Output the PDF as a string
        $pdfOutput = $dompdf->output();
        // Send the PDF as a response with a "Content-Type" header of "application/pdf"
        $dompdf->stream("{{coach.prenom}}+cv.pdf", [
            "attachment" => true,
        ]);
        $this->redirectToRoute('show_cv');
        return new Response();
    }
}
