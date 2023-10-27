<?php

namespace App\Repository;

use App\Entity\EventRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventRequest>
 *
 * @method EventRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventRequest[]    findAll()
 * @method EventRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventRequest::class);
    }

    public function save(EventRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(EventRequest $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return EventRequest[] Returns an array of EventRequest objects
    */
   public function findByExampleField($value): array
   {
       return $this->createQueryBuilder('e')
           ->andWhere('e.exampleField = :val')
           ->setParameter('val', $value)
           ->orderBy('e.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

    //   $query = $this->createQueryBuilder('u')
    //         ->select('kid.id', 'kid.firstname', 'kid.lastname')
    //         ->leftJoin('u.kidHasUsers', 'kidhasUser')
    //         ->leftJoin('kidhasUser.kid', 'kid')
    //         ->andWhere('kidhasUser.user = :userId')
    //         ->setParameter('userId', $userId)
    //         //->andWhere('kid.id = kid.id')
    //         ->getQuery()
    //         ->getResult();


    //     //dd($query);
    //     return $query;
//    public function findOneBySomeField($value): ?EventRequest
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
