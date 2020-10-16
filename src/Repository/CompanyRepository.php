<?php


namespace App\Repository;


use Doctrine\ORM\EntityRepository;

/**
 * Class CompanyRepository
 *
 * @package App\Repository
 */
class CompanyRepository extends EntityRepository
{
    public function getActiveCompanies(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.active = :active')
            ->setParameter(':active', true)
            ->getQuery()
            ->getResult();
    }
}