<?php

namespace App\Domain\EventSubscriber;

use App\Domain\Event\CreateUserEvent;
use App\Domain\Services\UserService;
use App\Infrastructure\Storage\MetricsStorage;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly LoggerInterface $elasticsearchLogger, private readonly MetricsStorage $metricsStorage)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            CreateUserEvent::class => 'onCreateUser',
        ];
    }
    public function onCreateUser(CreateUserEvent $event): void
    {
        $this->elasticsearchLogger->info("User {$event->login} created}");
        $this->metricsStorage->increment(MetricsStorage::USER_CREATED);
    }
}
