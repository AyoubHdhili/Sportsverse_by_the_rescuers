<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAllWithFilters($filters)
    {

        $qb = $this->createQueryBuilder('p');

        foreach($filters as $categorie) {
            $qb->orWhere('p.categorie_list LIKE :categorie_'.$categorie)->setParameter('categorie_'.$categorie, '%'.$categorie.'%');
        }

        // $colors = implode(", ", $filters);
        // $qb->where('p.color_list IN (:colors)')->setParameter('colors', $colors);


        return $qb->getQuery()->getResult();
    }

    public function findAllPerCategoryWithFilters($filters, $categorieId)
    {

        $qb = $this->createQueryBuilder('p');

       

        foreach($filters as $nomc) {
            $qb->orWhere('p.nom_list LIKE :nom_'.$nomc)->setParameter('nom_'.$nomc, '%'.$nomc.'%');
        }

        $qb->andWhere('p.categorie = :categorieId')
            ->setParameter('categorieId' , $categorieId);

        return $qb->getQuery()->getResult();
    }
    public function findAllByName($filterName) {
        $qb = $this->createQueryBuilder('p');
        $qb->andWhere('p.nom LIKE :nom')
            ->setParameter('nom','%'.$filterName.'%');

        return $qb->getQuery()->getResult();
    }
//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}



