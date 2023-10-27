<?php

namespace App\Repository;

use App\Entity\GroupHasKid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupHasKid>
 *
 * @method GroupHasKid|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupHasKid|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupHasKid[]    findAll()
 * @method GroupHasKid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupHasKidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupHasKid::class);
    }

    public function save(GroupHasKid $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GroupHasKid $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchIdToGroup($kid)
    {
        $query = $this->createQueryBuilder('k')
            ->select('kid.firstname', 'kid.lastname', 'kid.birthdate')
            ->leftJoin('k.GoupHasKid', 'GroupHasKid')
            ->leftJoin('GroupHasKid.kid', 'Kid')
            ->andWhere('GroupHasKid.group = :group_id')
            ->setParameter('group_id', $kid)
            ->getQuery()
            ->getResult();
        dd($query);
        return $query;
    }

    // public function findOneByKidIdJoinedToGroup($kidId): ?array
    // {
    //     $entityManager = $this->getEntityManager();

    // $query = $entityManager->createQuery(
    //     'SELECT u.id
    //     FROM App\Entity\User u
    //     INNER JOIN App\Entity\KidHasUser k
    //     ON u.id = k.user_id
    //     INNER JOIN App\Entity\Kid kid
    //     ON k.kid_id = kid.id'
    //     // -- WHERE u.id = :id'
    // )->setParameter('id', $userId);

    // $query = $entityManager->createQuery(
    //     'SELECT k
    //     FROM App\Entity\User u
    //     INNER JOIN u.kidHasUsers kha
    //     INNER JOIN  App\Entity\KidHasUser.kid k
    //    '
    // );

    // 'u.id AS user_id', 'kidhasUser.id As kidHasUsers_id'
    //     $query = $this->createQueryBuilder('u')
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
    // }


    //    /**
    //     * @return GroupHasKid[] Returns an array of GroupHasKid objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GroupHasKid
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
