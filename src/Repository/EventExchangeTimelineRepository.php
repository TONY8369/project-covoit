<?php

namespace App\Repository;

use App\Entity\EventExchangeTimeline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventExchangeTimeline>
 *
 * @method EventExchangeTimeline|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventExchangeTimeline|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventExchangeTimeline[]    findAll()
 * @method EventExchangeTimeline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventExchangeTimelineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventExchangeTimeline::class);
    }

    public function save(EventExchangeTimeline $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventExchangeTimeline $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return EventExchangeTimeline[] Returns an array of EventExchangeTimeline objects
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

//    public function findOneBySomeField($value): ?EventExchangeTimeline
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
