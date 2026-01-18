<?php

namespace App\Application\Validation\Constraints;

use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute]
class EntityExist extends Constraint
{

    #[HasNamedArguments]
    public function __construct(
        public string $entityClass,
        public ?string $message = 'The entity with ID "{{ id }}" does not exist.',
        public ?string $idProperty = 'id',
        ?array $groups = null,
               $payload = null,
        array $options = []
    )
    {
        $options['message'] = $message;
        $options['idProperty'] = $idProperty;
        $options['entityClass'] = $entityClass;
        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }

    public function getDefaultOption(): string
    {
        return 'entityClass';
    }

    public function getRequiredOptions(): array
    {
        return ['entityClass'];
    }
}
