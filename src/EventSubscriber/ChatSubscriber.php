<?php


namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ChatSubscriber
 *
 * @package App\EventSubscriber
 */
class ChatSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
       return [

       ];
    }
}