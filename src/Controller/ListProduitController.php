<?php

namespace App\Controller;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitRepository;
class ListProduitController extends AbstractController
{
   
    
    #[Route('/list/produit', name: 'app_list_produit', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('list_produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
}
