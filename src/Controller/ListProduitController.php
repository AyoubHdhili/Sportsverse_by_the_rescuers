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
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Review;
use App\Form\ReviewType;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
#[Route('/list/produit')]
class ListProduitController extends AbstractController
{
   
    
    #[Route('/', name: 'app_list_produit', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository , CategorieRepository $categorieRepository,PaginatorInterface $paginator ,Request $request,CacheInterface $cache): Response
    { 
        $limit = 6;

        
        $page = (int)$request->query->get("page", 1);

      
        $filters = $request->get("categories");
        
        $produits =$produitRepository->getPaginatedProduits($page, $limit, $filters);
       
        $total = $produitRepository->getTotalProduits($filters);
        


        $pagination = $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            6
        );

       
       if($request->get('ajax')){
        return new JsonResponse([
            'content' => $this->renderView('produit/_content/index.html.twig', compact('produits', 'total', 'limit', 'page'))
        ]);
       }
       $categories = $cache->get('app_categorie_index', function(ItemInterface $item) use($categorieRepository){
        $item->expiresAfter(3600);

        return $categorieRepository->findAll();
    });

        $categorieRepository ;
        $categories = $categorieRepository->findAll();

        
        

        return $this->render('list_produit/index.html.twig', [
            
            'produits' => $produitRepository->findAll(),
            'pagination' => $pagination,
            'categories' => $categories,
        ]);
    }
   


     #[Route("/{id}", name:"product_show")]  
    public function show(Produit $produit, Request $request,  ReviewRepository $reviewRepository, EntityManagerInterface $entityManager)
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
                $user = $this->getUser();
                $user = $entityManager
            ->getRepository(User::class)
            ->find(1);
                $review->setUser($user);
                $review->setDateCreation(new DateTime('NOW'));
                $reviewRepository->save($review, true);

                return $this->redirectToRoute('product_show', [ 'id' => $produit->getId()]);
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

