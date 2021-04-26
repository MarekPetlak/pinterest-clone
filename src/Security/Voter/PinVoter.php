<?php

namespace App\Security\Voter;

use App\Entity\Pin;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PinVoter extends Voter
{
    public const PIN_EDIT   = 'PIN_EDIT';
    public const PIN_DELETE = 'PIN_DELETE';
    public const PIN_CREATE = 'PIN_CREATE';

    public const PIN_ACTIONS = [
        self::PIN_EDIT,
        self::PIN_DELETE,
        self::PIN_CREATE,
    ];

    protected function supports($attribute, $subject)
    {
        if ($attribute === self::PIN_CREATE) {
            return true;
        }

        return in_array($attribute, self::PIN_ACTIONS, true)
            && $subject instanceof Pin;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$user->isVerified()) {
            return false;
        }

        switch ($attribute) {
            case self::PIN_CREATE:
                return true;
            case self::PIN_EDIT:
            case self::PIN_DELETE:
                return $subject->getUser() == $user;
        }

        return false;
    }
}
