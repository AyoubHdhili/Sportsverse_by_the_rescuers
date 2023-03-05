<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Review;
use App\Form\ReviewType;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;

#[Route('/list/produit')]
class ListProduitController extends AbstractController
{
   
    
    #[Route('/', name: 'app_list_produit', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository , CategorieRepository $categorieRepository,PaginatorInterface $paginator ,Request $request,): Response
    { 
        $produitRepository;
        $produits =$produitRepository->findAll();
        

        $paginatorProduits = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            6
        );


       //dump($request->get('colors'));
        if (!empty($request->get('categorie'))) {

            $produits = $produitRepository->findAllWithFilters(
                $request->get('categorie')
            );
            dump($produits);
            
            $paginatorProduits = $paginator->paginate(
                $produits,
                $request->query->getInt('page', 1),
                6
            );
        }

        $categorieRepository ;
        $categories = $categorieRepository->findAll();

        /* DEBUT RECHERCHE DE PRODUITS*/
        if (!empty($request->get('filterName'))) {
            $produits = $produitRepository->findAll(
                $request->get('filterName')
            );
            $paginatorProduits = $paginator->paginate(
                $produits,
                $request->query->getInt('page', 1),
                6
            );
        }

        return $this->render('list_produit/index.html.twig', [
            
            'produits' => $produitRepository->findAll(),
            'paginatorProuits' => $paginatorProduits,
            
        ]);
    }
   


     #[Route("/{slug}_{id}", name:"product_show", requirements:["slug"=>"[a-z0-9\-]*"])]  
    public function show(Produit $produit, Request $request, $slug, ReviewRepository $reviewRepository, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        $imageFile = $form->get('image')->getData();
        $countReview = count($produit->getReviews());

        $nbre = 0;

        if ($countReview !== 0) {
            for ($i = 0; $i < $countReview; $i++) {
                $nbre += $produit->getReviews()[$i]->getnbre();
            }
            $nbre = $nbre / $countReview;
        }

        $review = new Review();
        $formReview = $this->createForm(ReviewType::class, $review);
        $formReview->handleRequest($request);
    

        $usernames = $reviewRepository->findAllUsernamesOfReviewsPerProduct($produit->getId());

        /* Faire le requête pour l'ajout de la review ici */
        /* #DEBUT [REQUEST FOR ADD REVIEW] */
            if($formReview->isSubmitted() && $formReview->isValid()){
                $review->setProduit($produit);
                /** @var User $user
                */
               /** $user = $this->getUser();*/
                $user = $entityManager
            ->getRepository(User::class)
            ->find(1);
                $review->setUser($user);
                $review->setDateCreation(new DateTime('NOW'));
                $reviewRepository->save($review, true);

                return $this->redirectToRoute('product_show', ['slug' => $slug, 'id' => $produit->getId()]);
            }
        /* #FIN [REQUEST FOR ADD REVIEW] */


        return $this->render('list_produit/view.html.twig', [
            'produit' => $produit,
            'countReview' => $countReview,
            'nbre' => $nbre,
            'formReview' => $formReview->createView(),
            'usernames' => $usernames,
        ]);
    }
}
