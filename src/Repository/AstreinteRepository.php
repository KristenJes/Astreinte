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
