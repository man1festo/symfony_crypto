<?php

namespace App\Application\EventListener;

use App\Controller\DTO\OutputDTOInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class KernelViewEventListener
{
    public function __construct(private readonly SerializerInterface $serializer)
    {

    }
    #[AsEventListener(event: KernelEvents::VIEW)]
    public function onKernelView(ViewEvent $event): void
    {
        $dto = $event->getControllerResult();
        if($dto instanceof OutputDTOInterface) {
            $event->setResponse($this->getKernelViewResponse($dto));
        }
    }

    private function getKernelViewResponse(OutputDTOInterface $dto): Response
    {
        $serializedData = $this->serializer->serialize($dto, 'json');
        return new JsonResponse($serializedData, Response::HTTP_OK, [], true);
    }
}
