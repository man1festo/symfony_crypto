<?php

namespace App\Controller\Web\CreateAccount\v1;

use App\Controller\Web\CreateAccount\v1\Input\CreateAccountDTO;
use App\Controller\Web\CreateAccount\v1\Output\CreatedAccountDTO;
use App\Domain\Model\CreateAccountModel;
use App\Domain\Services\AccountService;
use App\Domain\Services\MakeModelService;
use App\Domain\ValueObject\CurrencyEnum;

class Manager
{
    public function __construct(private readonly MakeModelService $makeModelService, private readonly AccountService  $accountService)
    {

    }

    public function createAccount(CreateAccountDTO $dto): CreatedAccountDTO
    {
        $model = $this->makeModelService->makeModel(CreateAccountModel::class, $dto->userId, CurrencyEnum::from($dto->type));
        $account = $this->accountService->createAccount($model);
        return new CreatedAccountDTO(
            $account->getId(),
            $account::class,
            $account->getBalance()
        );

    }
}
