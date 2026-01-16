<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @return User[] Returns active users
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('u.lastName', 'ASC')
            ->addOrderBy('u.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get staff count by gender
     */
    public function getStaffCountByGender(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.gender, COUNT(u.id) as count')
            ->where('u.isActive = :active')
            ->setParameter('active', true)
            ->groupBy('u.gender')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get staff count by department
     */
    public function getStaffCountByDepartment(): array
    {
        return $this->createQueryBuilder('u')
            ->select('d.name as department, COUNT(u.id) as count')
            ->leftJoin('u.department', 'd')
            ->where('u.isActive = :active')
            ->setParameter('active', true)
            ->groupBy('d.id')
            ->getQuery()
            ->getResult();
    }
}
