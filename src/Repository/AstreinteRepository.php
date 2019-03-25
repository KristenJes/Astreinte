<?php

namespace App\Repository;

use App\Entity\Astreinte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Astreinte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Astreinte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Astreinte[]    findAll()
 * @method Astreinte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AstreinteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Astreinte::class);
    }

    public function findByYear($year, $semaine = 1, $order = "ASC")
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.annee = :annee')
            ->andWhere('a.semaine >= :semaine')

            ->setParameter('annee', $year)
            ->setParameter('semaine', $semaine)

            ->addOrderBy('a.annee', $order)
            ->addOrderBy('a.semaine', $order)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByIds(array $vals)
    {
        return $this->createQueryBuilder('a')
                    ->andWhere('a.annee = :annee')
                    ->andWhere('a.semaine = :semaine')
                    ->setParameter('annee', $vals['annee'])
                    ->setParameter('semaine', $vals['semaine'])
                    ->getQuery()
                    ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Astreinte[] Returns an array of Astreinte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Astreinte
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
