<?php

namespace App\Controller\Web\CreateUser\v1\Output;

use App\Controller\DTO\OutputDTOInterface;

class CreatedUserDto implements OutputDTOInterface
{
    public function __construct(
        public string $login,
        public int $id,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public array $saleOrders,
        public array $purchaseOrders,
        public array $userAccounts,
    )
    {
    }
}
