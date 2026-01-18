<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\Order;
use App\Domain\Entity\User;

interface OrderRepositoryInterface
{
    public function getOrdersPaginated(int $page, int $limit): ?array;
    public function create(Order $order):int;
    public function findOrdersToSaleRecursive(float $total, array $orders = []): array;
    public function findOrdersToBuyRecursive(float $total, array $orders = []): array;
    public function remove(Order $order): void;
    public function approveSell(Order $order): void;
    public function setBuyerToOrder(Order $order, User $user, Account $account): void;
    public function setSellerToOrder(Order $order, User $user, Account $account): void;


}
