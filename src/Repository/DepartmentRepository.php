<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Department>
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    /**
     * @return Department[] Returns active departments
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get departments with staff counts
     */
    public function findWithStaffCounts(): array
    {
        return $this->createQueryBuilder('d')
            ->select('d.id, d.name, d.code, COUNT(u.id) as staffCount')
            ->leftJoin('d.users', 'u', 'WITH', 'u.isActive = true')
            ->where('d.isActive = :active')
            ->setParameter('active', true)
            ->groupBy('d.id')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
