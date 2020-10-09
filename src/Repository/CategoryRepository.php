<?php


namespace App\Repository;


use App\Entity\Category;
use App\Entity\Job;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    /**
     * @return Category[]
     */
    public function findWithActiveJobs()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.jobs', 'j')
            ->where('j.expiresAt > :date')
            ->setParameter('date', new \DateTime())
            ->getQuery()
            ->getResult();
    }
}