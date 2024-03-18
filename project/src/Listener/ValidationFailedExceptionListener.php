<?php

namespace App\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: 'kernel.exception')]
class ValidationFailedExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable()->getPrevious();

        if (!$exception instanceof ValidationFailedException) {
            return;
        }

        $response = new JsonResponse([
            'message' => 'Validation failed',
            'errors' => array_map(fn($violation) => ['field' => $violation->getPropertyPath(), 'message' => $violation->getMessage()], iterator_to_array($exception->getViolations())),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        $event->setResponse($response);
    }
}