<?php

declare(strict_types=1);

namespace Shared\Domain\Model;

interface AggregateRootInterface
{
    public function releaseEvents(): array;
}
