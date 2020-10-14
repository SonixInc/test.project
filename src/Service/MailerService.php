<?php


namespace App\Service;


use App\Entity\Affiliate;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Send message when affiliate is active
     *
     * @param Affiliate $affiliate
     *
     * @throws TransportExceptionInterface Throws when an error occurs in sending message
     */
    public function sendActivationEmail(Affiliate $affiliate): void
    {
        $message = (new TemplatedEmail())
            ->from('jobeet@example.com')
            ->to($affiliate->getEmail())
            ->subject('Affiliate activated')
            ->htmlTemplate('mail/affiliate_activation.html.twig')
            ->context([
                'token' => $affiliate->getToken()
            ]);                                                        

        $this->mailer->send($message);
    }
}