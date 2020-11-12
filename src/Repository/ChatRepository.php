<?php


namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * Class ChatRepository
 *
 * @package App\Repository
 */
class ChatRepository extends EntityRepository
{
    /**
     * @param int $userId
     *
     * @return int|mixed|string
     */
    public function getMeInvitedChats(int $userId)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.users', 'uc')
            ->where('uc.id = :id')
            ->setParameter(':id', $userId)
            ->getQuery()
            ->getResult();
    }
}
