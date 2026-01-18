<?php

namespace App\Infrastructure\Bus;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class RabbitMqBus
{
    /** @var array<string, ProducerInterface>  */
    private array $producers;

    public function __construct(private readonly SerializerInterface $serializer)
    {
        $this->producers = [];
    }

    public function registerProducer(AmqpExchangeEnum $amqpExchangeEnum, ProducerInterface $producer): void
    {
        $this->producers[$amqpExchangeEnum->value] = $producer;
    }

    public function publishToExchange(AmqpExchangeEnum $amqpExchangeEnum, $message, ?string $routingKey = null, ?array $additionalParams = null): bool
    {
        $serializedMessage = $this->serializer->serialize($message, 'json', [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]);
        if (isset($this->producers[$amqpExchangeEnum->value])) {
            $this->producers[$amqpExchangeEnum->value]->publish($serializedMessage, $routingKey ?? '', $additionalParams ?? []);
            return true;
        }
        return false;
    }

    public function publishMultipleToExchange(AmqpExchangeEnum $amqpExchangeEnum, array $messages, ?string $routingKey = null, ?array $additionalParams = null): int
    {
        foreach ($messages as $message) {
            $this->publishToExchange($amqpExchangeEnum, $message, $routingKey, $additionalParams);
        }
        return count($messages);
    }
}
