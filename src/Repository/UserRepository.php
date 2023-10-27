<?php

namespace App\Repository;

use App\Entity\Kid;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByIdJoinedToUser($userId): ?array
    {
        $entityManager = $this->getEntityManager();

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
        $query = $this->createQueryBuilder('u')
            ->select('kid.id', 'kid.firstname', 'kid.lastname')
            ->leftJoin('u.kidHasUsers', 'kidhasUser')
            ->leftJoin('kidhasUser.kid', 'kid')
            ->andWhere('kidhasUser.user = :userId')
            ->setParameter('userId', $userId)
            //->andWhere('kid.id = kid.id')
            ->getQuery()
            ->getResult();


        //dd($query);
        return $query;
    }


    // public function findOneByIdJoinedToKid(int $userId): ?User
    // {
    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery(
    //         'SELECT user, kid
    //         FROM App\Entity\Kid k
    //         INNER JOIN k.kidHasUsers
    //         ON kid.id = kidHasUsers.kid_id
    //         WHERE k.id = :id'
    //     )->setParameter('id', $userId);
    //     dd($query);
    //    return $query->getResult();

    // }

    public function searchByUserCivility($civility)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u.civility,
            FROM App\Entity\User u
            WHERE u.civility = :civility
            ORDER BY u.civility ASC
            '
        )->setParameter('id', $civility);

        return $query->getResult();
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByEmail(string $email)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.email = :email'
        )->setParameter('email', $email);
        // dd($query->getResult());
        // returns an array of Product objects
        return $query->getResult();
        // $conn = $this->getEntityManager()->getConnection();
        // $sql = '
        //     SELECT * FROM user u
        //     WHERE u.email = :email
        // ';
        // $stmt = $conn->prepare($sql);
        // $resustSet =  $stmt->executeQuery(['email' => $email]);

        // return $resultSet->fetchAllAssociative();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
