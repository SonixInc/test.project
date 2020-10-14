<?php


namespace App\EventSubscriber;


use App\Entity\Affiliate;
use App\Service\MailerService;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerService
     */
    private $mailer;

    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityUpdatedEvent::class => ['affiliateActiveMail'],
        ];
    }

    /**
     * Send message when affiliate is active
     *
     * @param AfterEntityUpdatedEvent $afterEvent After update event listener
     *
     * @throws TransportExceptionInterface Throws when an error occurs in sending message
     */
    public function affiliateActiveMail(AfterEntityUpdatedEvent $afterEvent): void
    {
        $entity = $afterEvent->getEntityInstance();

        if (!$entity instanceof Affiliate) {
            return;
        }

        if (!$entity->isActive()) {
            return;
        }

        $this->mailer->sendActivationEmail($entity);
    }
}