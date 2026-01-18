<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Account;
use App\Domain\Entity\Order;
use App\Domain\Entity\User;
use App\Domain\Model\OrderModel;
use App\Infrastructure\Storage\MetricsStorage;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class OrderRepositoryCacheDecorator implements OrderRepositoryInterface
{

    public function __construct(private readonly OrderRepository $orderRepository, private readonly TagAwareCacheInterface $cache, private readonly MetricsStorage $metricsStorage)
    {

    }

    public function getOrdersPaginated(int $page, int $limit): array
    {
        $cacheKey = "ordersPaginated_{$page}_{$limit}";
        $result = $this->cache->get($cacheKey, function (ItemInterface $item) use ($page, $limit) {
            $orders = array_map(static fn (Order $order): OrderModel => new OrderModel(
                $order->getId(),
                $order->getSeller()?->getLogin(),
                $order->getBuyer()?->getLogin(),
                $order->getAmountToBuy(),
                $order->getAmountToSell(),
                $order->getCreatedAt()
            ), $this->orderRepository->getOrdersPaginated($page, $limit));
            $item->set($orders);
            $item->tag('orders');
            return $orders;
        }, null, $metadata);
        $metric = ([] !== $metadata) ? MetricsStorage::CACHE_HIT_PREFIX : MetricsStorage::CACHE_MISS_PREFIX;
        $this->metricsStorage->increment($metric.$cacheKey);
        return $result;
    }

    public function create(Order $order):int
    {
        $this->cache->invalidateTags(['order']);
        return $this->orderRepository->create($order);
    }
    public function findOrdersToSaleRecursive(float $total, array $orders = []): array
    {
        return $this->orderRepository->findOrdersToSaleRecursive($total, $orders);
    }
    public function findOrdersToBuyRecursive(float $total, array $orders = []): array
    {
        return $this->orderRepository->findOrdersToBuyRecursive($total, $orders);
    }
    public function remove(Order $order): void
    {
        $this->orderRepository->remove($order);
    }
    public function approveSell(Order $order): void
    {
        $this->orderRepository->approveSell($order);
    }
    public function setBuyerToOrder(Order $order, User $user, Account $account): void
    {
        $this->orderRepository->setBuyerToOrder($order, $user, $account);
    }
    public function setSellerToOrder(Order $order, User $user, Account $account): void
    {
        $this->orderRepository->setSellerToOrder($order, $user, $account);
    }
}
