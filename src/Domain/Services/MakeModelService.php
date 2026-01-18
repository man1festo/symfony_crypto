<?php

namespace App\Domain\Services;

use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MakeModelService
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function makeModel(string $modelClass, ...$params)
    {
        $model = new $modelClass(...$params);
        $errors = $this->validator->validate($model);
        if (count($errors) > 0) {
            throw new ValidationFailedException($params, $errors);
        }
        return $model;
    }
}
