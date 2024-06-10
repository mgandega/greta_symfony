<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 *
 * @method Conference|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conference|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conference[]    findAll()
 * @method Conference[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    /**
     * @return Conference[] Returns an array of Conference objects
     */
    // public function findByTitleLength($value): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.date <= :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('c.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }
    public function conferenceParDate($value): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.date <= :val')
            ->setParameter('val', $value.' 00-00-00')
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Conference[] Returns an array of Conference objects
     */
    // public function findUnique(): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.nom <= :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('c.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    //     // $categories = $this->getRepository(Categorie::class)->createQueryBuilder('c')
    //     //     ->select('DISTINCT c.name')
    //     //     ->getQuery()
    //     //     ->getResult();
    // }
    /**
     * @return Conference[] Returns an array of Conference objects
     */

    /**
     * @return Conference[] Returns an array of Conference objects
     */
    public function conferencesParCategorie($value): array
    {
        return $this->createQueryBuilder('conf')
            ->innerJoin('conf.categorie', 'c')
            ->andWhere('c.nom = :val')
            ->setParameter('val', $value)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

    }
    /**
     * @return Conference[] Returns an array of Conference objects
     */
    public function recherche($date,$prix,$categorie)
    {
       $qb =  $this->createQueryBuilder('conf')
            ->innerJoin('conf.categorie', 'categorie');
            if($date != null){
                $qb->andWhere('conf.date <= :val')
                ->setParameter('val', $date." 00:00:00");
            }
            if($prix != null){
                $qb->andWhere('conf.prix <= :val')
                ->setParameter('val', $prix);
            }
            if($categorie != null){
                // dd($categorie);  
                $qb->andWhere('categorie.nom= :val')
                ->setParameter('val', $categorie);
            }
           return $qb->getQuery();
        //    ->getResult();
    }
    /**
     * @return Conference[] Returns an array of Conference objects
     */
    public function mes5dernieresConferences(): array
    {
        return $this->createQueryBuilder('conf')
            ->setMaxResults(5)
            ->orderBy('conf.id', 'DESC')
            ->getQuery()
            ->getResult();

    }

    //    public function findOneBySomeField($value): ?Conference
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
