<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function barDep(){
        return $this->createQueryBuilder('r')
        ->select('count(r.id)')
        ->where('r.etat LIKE :reclamation')
        // ->where('r.typee LIKE : reclamation')
        ->setParameter('reclamation','en cours ')
        ->getQuery()
        ->getSingleScalarResult();
    }
    
    public function barArr(){
        return $this->createQueryBuilder('r')
        ->select('count(r.id)')
         ->where('r.etat LIKE :reclamation')
        // ->where('r.typee LIKE :  reclamation')
        ->setParameter('reclamation','traite')
        ->getQuery()
        ->getSingleScalarResult();
    }
    public function SortBysujet(){
        return $this->createQueryBuilder('e')
            ->orderBy('e.sujet','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    
    public function SortBydate()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.date','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function SortBynom()
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.nomClient','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findBysujet( $sujet)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.sujet LIKE :sujet')
        ->setParameter('sujet','%' .$sujet. '%')
        ->getQuery()
        ->execute();
}
public function findBynom( $nomClient)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.nomClient LIKE :nomClient')
        ->setParameter('nomClient','%' .$nomClient. '%')
        ->getQuery()
        ->execute();
}
public function findBydate( $date)
{
    return $this-> createQueryBuilder('e')
        ->andWhere('e.date LIKE :date')
        ->setParameter('date','%' .$date. '%')
        ->getQuery()
        ->execute();
}

//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
class MyEntityy
{
    public function __toString() {
        return $this->somePropertyOrPlainString;
    }
}