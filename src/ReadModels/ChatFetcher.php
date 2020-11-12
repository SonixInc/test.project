<?php


namespace App\ReadModels;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

/**
 * Class ChatFetcher
 *
 * @package App\ReadModels
 */
class ChatFetcher
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }



    public function getInvitedChatsForUser(int $userId): array
    {
        $stmt = $this->connection->createQueryBuilder()
            ->select(
                'c.id',
                'c.name',
                '(
                    SELECT COUNT(um.id)
                    FROM user_messages AS um
                    WHERE um.user_id = :id AND um.viewed = :viewed
                ) AS message_count'
            )
            ->from('user_chats', 'c')
            ->where('c.id = :id')
            ->setParameter(':id', $userId)
            ->setParameter(':viewed', 0)
            ->execute();

        return $stmt->fetchAll(FetchMode::ASSOCIATIVE);
    }
}
