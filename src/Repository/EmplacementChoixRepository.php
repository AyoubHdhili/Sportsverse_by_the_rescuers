<?php

namespace App\Repository;

use App\Entity\EmplacementChoix;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmplacementChoix>
 *
 * @method EmplacementChoix|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmplacementChoix|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmplacementChoix[]    findAll()
 * @method EmplacementChoix[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmplacementChoixRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmplacementChoix::class);
    }

    public function save(EmplacementChoix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EmplacementChoix $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findGovernorat(){
        $governorats=$this->createQueryBuilder('g')
        ->getQuery()
        ->getResult();
        $choices=[];
        foreach($governorats as $governorat){
            $choices[$governorat->getGovernorat()]=$governorat->getGovernorat();
        }
        return $choices;
    }
    public function findDelegation(){
        $delegations=$this->createQueryBuilder('d')
        ->getQuery()
        ->getResult();
        $choices=[];
        foreach($delegations as $delegation){
            $choices[$delegation->getDelegation()]=$delegation->getDelegation();
        }
        return $choices;
    }
    public function findLocalite(){
        $localites=$this->createQueryBuilder('l')
        ->getQuery()
        ->getResult();
        $choices=[];
        foreach($localites as $localite){
            $choices[$localite->getLocalite()]=$localite->getLocalite();
        }
        return $choices;
    }
//    /**
//     * @return EmplacementChoix[] Returns an array of EmplacementChoix objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmplacementChoix
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
