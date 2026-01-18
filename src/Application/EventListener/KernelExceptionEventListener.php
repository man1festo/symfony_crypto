<?php

namespace App\Application\EventListener;

use App\Controller\Exception\HttpCompliantExceptionInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class KernelExceptionEventListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if($exception instanceof HttpExceptionInterface && $exception->getPrevious() instanceof ValidationFailedException) {
            $event->setResponse($this->getValidationFailedResponse($exception->getPrevious()));
        } elseif ($exception instanceof HttpCompliantExceptionInterface) {
            $event->setResponse(new JsonResponse([$exception->getHttpResponseBody()], $exception->getHttpCode()));
        } elseif($exception instanceof BadRequestHttpException) {
            $event->setResponse(new JsonResponse([$exception->getMessage()], Response::HTTP_BAD_REQUEST));
        } elseif($exception instanceof UniqueConstraintViolationException) {
            $event->setResponse(new JsonResponse([$exception->getMessage()], Response::HTTP_BAD_REQUEST));
        } elseif($exception instanceof ValidationFailedException) {
            $event->setResponse($this->getValidationFailedResponse($exception));
        }
    }

    private function getValidationFailedResponse(ValidationFailedException $e): JsonResponse
    {
        $response = [];
        foreach ($e->getViolations() as $violation){
            $response[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return new JsonResponse($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
