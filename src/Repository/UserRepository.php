<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

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
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function save(User $user): void
    {
        $this->entityManager->beginTransaction();

        try 
        {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } 
        catch (\Exception $exception) 
        {
            $this->entityManager->rollback();
        }
    }

    public function delete(User $user): void
    {
        $this->entityManager->beginTransaction();

        try 
        {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } 
        catch (\Exception $exception) 
        {
            $this->entityManager->rollback();
        }
    }

    public function findAllPaginated($page = 1, $limit = 10)
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($queryBuilder);

        return $paginator;
    }
}
