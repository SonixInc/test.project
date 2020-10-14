<?php


namespace App\Repository;


use App\Entity\Affiliate;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

class AffiliateRepository extends EntityRepository
{
    /**
     * Find active affiliate by token
     *
     * @param string $token
     *
     * @return Affiliate|null
     * @throws NonUniqueResultException Throws when there are more than one result
     */
    public function findOneActiveByToken(string $token) : ?Affiliate
    {
        return $this->createQueryBuilder('a')
            ->where('a.active = :active')
            ->andWhere('a.token = :token')
            ->setParameter('active', true)
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}