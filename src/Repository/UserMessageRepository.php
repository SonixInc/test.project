<?php


namespace App\Repository;


use App\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserMessageRepository
 *
 * @package App\Repository
 */
class UserMessageRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return int|mixed|string
     */
    public function findUserUnreadMessages(User $user): array
    {
        return $this->createQueryBuilder('um')
            ->where('um.user = :user')
            ->andWhere('um.viewed = :viewed')
            ->setParameter(':user', $user)
            ->setParameter(':viewed', false)
            ->getQuery()
            ->getResult();
    }
}