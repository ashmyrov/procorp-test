<?php

namespace App\Controller;

use App\Dto\Registration\RegistrationDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/registration', name: 'api_registration')]
class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    #[Route('', name: 'register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegistrationDto $registrationDto): JsonResponse
    {
        $this->userService->registration($registrationDto);
        return $this->json('', Response::HTTP_CREATED);
    }
}