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

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public static function getSubscribedEvents(): iterable
    {
        return [
            EmailConfirmationEvent::class => 'sendConfirmationEmail',
        ];
    }

    public function sendConfirmationEmail(EmailConfirmationEvent $event)
    {
        $user = $event->getUser();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('noreply@pinterest-clone.example', 'Pinterest Clone'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }
}