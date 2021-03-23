<?php

declare(strict_types=1);

namespace App;

use App\Entity\User;

class EmailConfirmationEvent
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}