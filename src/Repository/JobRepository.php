<?php


namespace App\Repository;


use App\Entity\Affiliate;
use App\Entity\Category;
use App\Entity\Company;
use App\Entity\Job;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use function Doctrine\ORM\QueryBuilder;

/**
 * Class JobRepository
 *
 * @package App\Repository
 */
class JobRepository extends EntityRepository
{
    /**
     * @param int|null $categoryId
     *
     * @return Job[]
     */
    public function findActiveJobs(int $categoryId = null): array
    {
        $qb = $this->createQueryBuilder('j')
            ->where('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->orderBy('j.expiresAt', 'DESC');

        if ($categoryId) {
            $qb->andWhere('j.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     *
     * @return Job|null
     * @throws NonUniqueResultException Throws when there are more than one result
     */
    public function findActiveJob(int $id) : ?Job
    {
        return $this->createQueryBuilder('j')
            ->where('j.id = :id')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('id', $id)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Category $category
     *
     * @param null     $search
     *
     * @return AbstractQuery
     */
    public function getPaginatedActiveJobsByCategoryQuery(Category $category, $search = null) : AbstractQuery
    {
        $qb = $this->createQueryBuilder('j')
            ->innerJoin('j.company', 'c')
            ->where('j.category = :category')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('category', $category)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true);

        if ($search) {
            $qb->andWhere($qb->expr()->like('LOWER(j.location)', ':search'))
                ->orWhere($qb->expr()->like('LOWER(j.position)', ':search'))
                ->orWhere($qb->expr()->like('LOWER(c.name)', ':search'))
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        return $qb->getQuery();
    }

    public function getPaginatedActiveJobsByCompanyQuery(Company $company): AbstractQuery
    {
        return $this->createQueryBuilder('j')
            ->where('j.company = :company')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter(':company', $company)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery();
    }

    /**
     * @param Affiliate $affiliate
     *
     * @return Job[]
     */
    public function findActiveJobsForAffiliate(Affiliate $affiliate): array
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('j.category', 'c')
            ->leftJoin('c.affiliates', 'a')
            ->where('a.id = :affiliate')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('affiliate', $affiliate)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->orderBy('j.expiresAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}