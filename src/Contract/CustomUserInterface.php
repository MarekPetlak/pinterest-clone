<?php

declare(strict_types=1);

namespace App\Contract;

use Symfony\Component\Security\Core\User\UserInterface;

interface CustomUserInterface extends UserInterface
{
    public function getId(): int;
    public function getEmail(): string;
    public function setIsVerified(bool $isVerified): self;.

}