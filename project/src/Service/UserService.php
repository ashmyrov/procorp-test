<?php

namespace App\Service;

use App\Dto\Registration\RegistrationDto;
use App\Entity\Users;
use App\Repository\UserRepository;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function registration(RegistrationDto $registrationDto): void
    {
        $user = new Users();
        $user->setUsername($registrationDto->getUsername());
        $user->setPassword($registrationDto->getPassword());
        $this->userRepository->store($user);
    }
}