<?php

namespace App\Application\RabbitMq;

use Cassandra\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Exception\UnsupportedFormatException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractConsumer implements ConsumerInterface
{
    private readonly EntityManagerInterface $em;
    private readonly ValidatorInterface $validator;
    private readonly SerializerInterface $serializer;

    #[Required]
    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    #[Required]
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    abstract public function getMessageClass(): string;
    abstract public function handle($message): int;

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = $this->serializer->deserialize($msg->getBody(), $this->getMessageClass(),'json');
            $errors = $this->validator->validate($message);
            if (count($errors) > 0) {
                return $this->reject((string) $errors);
            }
            return $this->handle($message);
        } catch (\Throwable $exception) {
            return $this->reject($exception->getMessage());
        } finally {
            $this->em->clear();
            $this->em->getConnection()->close();
        }
    }

    protected function reject(string $message): int
    {
        echo "Incorrect message: $message\n";
        return self::MSG_REJECT;
    }
}
