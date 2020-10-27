<?php


namespace App\Chat;

use App\Entity\Message;
use App\Event\ChatMessageReadEvent;
use Doctrine\ORM\EntityManagerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ZMQ;
use ZMQContext;

/**
 * Class Chat
 *
 * @package App\Chat
 */
class BasePusher implements WampServerInterface
{
    protected $subscribedTopics = [];

    /**
     * @var \SplObjectStorage
     */
    private $clients;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * Chat constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->clients = new \SplObjectStorage();
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ConnectionInterface        $conn
     * @param \Ratchet\Wamp\Topic|string $topic
     */
    public function onSubscribe(ConnectionInterface $conn, $topic): void
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @param ConnectionInterface        $conn
     * @param \Ratchet\Wamp\Topic|string $topic
     */
    public function onUnSubscribe(ConnectionInterface $conn, $topic): void
    {
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        dd($conn->Session->get('user_id'));
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn): void
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * @param ConnectionInterface        $conn
     * @param string                     $id
     * @param \Ratchet\Wamp\Topic|string $topic
     * @param array                      $params
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params): void
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    /**
     * @param ConnectionInterface        $conn
     * @param \Ratchet\Wamp\Topic|string $topic
     * @param                            $event
     * @param array                      $exclude
     * @param array                      $eligible
     *
     * @throws \ZMQSocketException
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible): void
    {
        $data   = json_decode($topic, true);

        foreach ($this->clients as $client) {
            $this->dispatcher->dispatch(new ChatMessageReadEvent($client), ChatMessageReadEvent::NAME);
        }

        $this->sendDataToServer([
            'chat'     => 'onNewMessage',
            'username' => $data['username'],
            'message'  => $data['message'],
        ]);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * @param $data
     *
     * @throws \ZMQSocketException
     */
    public function sendDataToServer($data): void
    {
        $context = new ZMQContext();
        $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $socket->send(json_encode($data));
    }

    /**
     * @param $jsonDataToSend
     */
    public function broadcast($jsonDataToSend): void
    {
        $entryData = json_decode($jsonDataToSend, true);

        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['chat'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['chat']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }

}