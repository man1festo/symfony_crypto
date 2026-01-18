<?php

namespace App\Controller\Web\DepositIntoAccount\v1;

use App\Application\Voter\AccountVoter;
use App\Controller\Web\DepositIntoAccount\v1\Input\DepositIntoAccountDTO;
use App\Controller\Web\DepositIntoAccount\v1\Output\DepositedIntoAccountDTO;
use App\Domain\Model\DepositeIntoAccountModel;
use App\Domain\Services\AccountService;
use App\Domain\Services\MakeModelService;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class Manager
{
    public function __construct(
        private readonly MakeModelService $makeModelService,
        private readonly AccountService $accountService,
        private readonly AuthorizationCheckerInterface $authorizationChecker)
    {

    }

    public function deposite(DepositIntoAccountDTO $dto): DepositedIntoAccountDTO
    {
        $account = $this->accountService->findAccountById($dto->accountId);
        if (!$this->authorizationChecker->isGranted(AccountVoter::EDIT, $account)) {
            throw new AccessDeniedHttpException();
        }
        $model = $this->makeModelService->makeModel(DepositeIntoAccountModel::class, $dto->accountId, $dto->amount);
        $account = $this->accountService->depositeIntoAccount($model);
        return new DepositedIntoAccountDTO(
            $account->toArray()
        );
    }

}
