<?php

namespace App\Domain\Services;

use App\Controller\Web\DepositIntoAccount\v1\Output\DepositedIntoAccountDTO;
use App\Domain\Entity\Account;
use App\Domain\Entity\BitcoinAccount;
use App\Domain\Entity\DollarAccount;
use App\Domain\Entity\EthereumAccount;
use App\Domain\Entity\RubleAccount;
use App\Domain\Model\CreateAccountModel;
use App\Domain\Model\DepositeIntoAccountModel;
use App\Domain\ValueObject\CurrencyEnum;
use App\Infrastructure\Repository\AccountRepository;

class AccountService
{
    public function __construct(private readonly AccountRepository $accountRepository, private readonly UserService $userService)
    {

    }


    public function findAccountById(int $accountId): Account
    {
        $account = $this->accountRepository->find($accountId);
        if ($account === null) {
            throw new \RuntimeException('Account not found');
        }
        return $account;
    }

    public function createAccount(CreateAccountModel $createAccountModel): Account
    {
        $account = match ($createAccountModel->currency) {
            CurrencyEnum::Bitcoin => new BitcoinAccount(),
            CurrencyEnum::Ethereum => new EthereumAccount(),
            CurrencyEnum::Ruble => new RubleAccount(),
            CurrencyEnum::Dollar => new DollarAccount(),
        };
        $user = $this->userService->findUserById($createAccountModel->userId);
        if ($user === null) {
            throw new \RuntimeException('User not found');
        }
        $account->setUser($user);
        $account->setBalance(0);
        $id = $this->accountRepository->create($account);
        $account->setId($id);
        return $account;
    }

    public function depositeIntoAccount(DepositeIntoAccountModel $depositeIntoAccountModel): Account
    {
        $account = $this->findAccountById($depositeIntoAccountModel->accountId);
        $this->accountRepository->deposite($account, $depositeIntoAccountModel->amount);
        $this->accountRepository->refresh($account);
        return $account;
    }

}
