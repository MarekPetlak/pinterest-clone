<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\EmailConfirmationEvent;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;

class EmailConfirmationSubscriber implements EventSubscriberInterface
{
    private EmailVerifier $emailVerifier;
    private string $defaultSenderEmail;
    private string $defaultSenderName;

    public function __construct(
        EmailVerifier $emailVerifier,
        string $defaultSenderEmail,
        string $defaultSenderName
    ) {
        $this->emailVerifier = $emailVerifier;
        $this->defaultSenderEmail = $defaultSenderEmail;
        $this->defaultSenderName = $defaultSenderName;
    }

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EmailConfirmationEvent::class => 'sendConfirmationEmail',
        ];
    }

    public function sendConfirmationEmail(EmailConfirmationEvent $event): void
    {
        $user = $event->getUser();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address($this->defaultSenderEmail, $this->defaultSenderName))
                ->to(new Address($user->getEmail()))
                ->subject('Please Confirm your Email')
                ->htmlTemplate('emails/registration/confirmation.html.twig')
        );
    }
}