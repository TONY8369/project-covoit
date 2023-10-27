<?php

namespace App\Repository;

use App\Entity\AssociationHasEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssociationHasEvent>
 *
 * @method AssociationHasEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssociationHasEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssociationHasEvent[]    findAll()
 * @method AssociationHasEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationHasEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociationHasEvent::class);
    }

    public function save(AssociationHasEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AssociationHasEvent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AssociationHasEvent[] Returns an array of AssociationHasEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AssociationHasEvent
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
