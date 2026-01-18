<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\Order;
use App\Domain\Entity\User;

class OrderRepository extends AbstractRepository implements OrderRepositoryInterface
{
    public function create(Order $order):int
    {
        return $this->store($order);
    }
    /**
     * Возвращает массив сделок на продажу которые удовлетворяют заданной сумме на покупку
     * За раз возвращает 1 подходящее значение:
     * - либо первый найденный заказ покрывающий заданную сумму с минимальной разницей
     * - либо заказ не покрывающий но с максимальной суммой
     *
     * @param float $total
     * @param array $orders
     * @return array
     */
    public function findOrdersToSaleRecursive(float $total, array $orders = []): array
    {
        $orderIds = array_map(function (Order $order) {
            return $order->getId();
        }, $orders) ?? [];

        $subQueryGreater = $this->entityManager->createQueryBuilder()
            ->select('min(o2.amountToSell - :targetValue)')
            ->from(Order::class, 'o2')
            ->andWhere('o2.amountToSell >= :targetValue');
        if ($orderIds) {
            $subQueryGreater->andWhere($subQueryGreater->expr()->notIn('o2.id', ':ids'))
                ->setParameter('ids', $orderIds);
        }
        $subQueryGreater->setParameter('targetValue', $total)->getDQL();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Order::class, 'o')
            ->orderBy('o.amountToSell', 'DESC')
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->andX(
                'o.amountToSell >= :targetValue',
                    "o.amountToSell - :targetValue = ($subQueryGreater)"
                ),
                "o.amountToSell <= :targetValue",
            ))
            ->setParameter('targetValue', $total)
            ->setMaxResults(1);
        if ($orderIds) {
            $queryBuilder->andWhere($queryBuilder->expr()->notIn('o.id', ':ids'))
                ->setParameter('ids', $orderIds);
        }
        $order = $queryBuilder->getQuery()->getOneOrNullResult();
        if ($order && $order->getAmountToSell() < $total) {
            return self::findOrdersToSaleRecursive($total - $order->getAmountToSell(), array_merge([$order], $orders));
        } else {
            return $order ? array_merge([$order], $orders) : $orders;
        }
    }

    /**
     * Возвращает массив сделок на покупку которые удовлетворяют заданной сумме на продажу
     * За раз возвращает 1 подходящее значение:
     * - либо первый найденный заказ покрывающий заданную сумму с минимальной разницей
     * - либо заказ не покрывающий но с максимальной суммой
     *
     * @param float $total
     * @param array $orders
     * @return array
     */
    public function findOrdersToBuyRecursive(float $total, array $orders = []): array
    {
        $orderIds = array_map(function (Order $order) {
            return $order->getId();
        }, $orders) ?? [];

        $subQueryGreater = $this->entityManager->createQueryBuilder()
            ->select('min(o2.amountToBuy - :targetValue)')
            ->from(Order::class, 'o2')
            ->andWhere('o2.amountToBuy >= :targetValue');
        if ($orderIds) {
            $subQueryGreater->andWhere($subQueryGreater->expr()->notIn('o2.id', ':ids'))
                ->setParameter('ids', $orderIds);
        }
        $subQueryGreater->setParameter('targetValue', $total)->getDQL();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Order::class, 'o')
            ->orderBy('o.amountToBuy', 'DESC')
            ->andWhere($queryBuilder->expr()->orX(
                $queryBuilder->expr()->andX(
                    'o.amountToBuy >= :targetValue',
                    "o.amountToBuy - :targetValue = ($subQueryGreater)"
                ),
                "o.amountToBuy <= :targetValue",
            ))
            ->setParameter('targetValue', $total)
            ->setMaxResults(1);
        if ($orderIds) {
            $queryBuilder->andWhere($queryBuilder->expr()->notIn('o.id', ':ids'))
                ->setParameter('ids', $orderIds);
        }
        $order = $queryBuilder->getQuery()->getOneOrNullResult();
        if ($order && $order->getAmountToBuy() < $total) {
            return self::findOrdersToSaleRecursive($total - $order->getAmountToBuy(), array_merge([$order], $orders));
        } else {
            return $order ? array_merge([$order], $orders) : $orders;
        }
    }

    public function remove(Order $order): void
    {
        $order->setDeletedAt();
        $this->flush();
    }

    public function removeById(int $id): void
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if($order instanceof Order) {
            $this->remove($order);
        }
    }

    public function approveSell(Order $order): void
    {
        $order->setSellerApprove(true);
        $this->flush();
    }

    public function approveBuy(Order $order): void
    {
        $order->setBuyerApprove(true);
        $this->flush();
    }

    public function setBuyerToOrder(Order $order, User $user, Account $account): void
    {
        $order->setBuyer($user);
        $order->setBuyerAccount($account);
        $this->flush();
    }

    public function setSellerToOrder(Order $order, User $user, Account $account): void
    {
        $order->setSeller($user);
        $order->setSellerAccount($account);
        $this->flush();
    }

    public function findById(int $id): ?Order
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);
        if($order instanceof Order) {
            return $order;
        }
        return null;
    }

    public function getOrdersPaginated($page = 1, $limit = 10): ?array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Order::class, 'o')
            ->orderBy('o.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        $query = $queryBuilder->getQuery();
        return $query->enableResultCache(100, "orders_paginated_{$page}_{$limit}")->getResult();
    }
}
