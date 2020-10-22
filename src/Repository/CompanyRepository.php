<?php


namespace App\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class CompanyRepository
 *
 * @package App\Repository
 */
class CompanyRepository extends EntityRepository
{
    public function findActiveCompanies(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.active = :active')
            ->setParameter(':active', true)
            ->getQuery()
            ->getResult();
    }

    public function findActiveCompaniesForUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.active = :active')
            ->andWhere('c.user = :user')
            ->setParameter(':active', true)
            ->setParameter(':user', $user)
            ->getQuery()
            ->getResult();
    }
}