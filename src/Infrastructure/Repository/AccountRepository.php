<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Account;

class AccountRepository extends AbstractRepository
{
    public function create(Account $account ): int
    {
        return $this->store($account);
    }

    public function remove(Account $account): void
    {
        $account->setDeletedAt();
        $this->flush();
    }

    public function deposite(Account $account, float $amount):void
    {
        $account->setBalance($account->getBalance() + $amount);
        $this->flush();
    }

    public function removeById(int $id): void
    {
        $account = $this->entityManager->getRepository(Account::class)->find($id);
        if($account instanceof Account) {
            $this->remove($account);
        }
    }

    public function find(int $id): ?Account
    {
        $account = $this->entityManager->getRepository(Account::class)->find($id);
        if(!$account instanceof Account) {
            return null;
        }
        return $account;
    }
}
