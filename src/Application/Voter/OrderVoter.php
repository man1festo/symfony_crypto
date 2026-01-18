<?php

namespace App\Application\Voter;

use App\Domain\Entity\Order;
use App\Domain\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OrderVoter extends Voter
{
    public const APPROVE_SELL = 'approve_sell';
    public const APPROVE_BUY = 'approve_buy';
    public const VIEW = 'view';
    public const SET_BUYER = 'set_buyer';
    public const SET_SELLER = 'set_seller';

    public function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Order) {
            return false;
        }
        return in_array($attribute, [self::APPROVE_BUY, self::APPROVE_SELL, self::VIEW, self::SET_BUYER, self::SET_SELLER]);
    }

    public function voteOnAttribute(string $attribute, mixed $subject, mixed $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof VoterUserInterface) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles()))
        {
            return true;
        }
        /** @var Order $order */
        $order = $subject;

        return match($attribute) {
            self::APPROVE_SELL => $this->canApproveSell($order, $user),
            self::APPROVE_BUY => $this->canApproveBuy($order, $user),
            self::VIEW => $this->canView($order, $user),
            self::SET_SELLER => $this->canSetSeller($order, $user),
            self::SET_BUYER => $this->canSetBuyer($order, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canApproveSell(Order $order, VoterUserInterface $user): bool
    {
        return $order->getSeller() !== null && $order->getSeller()->getId() === $user->getId();
    }

    private function canApproveBuy(Order $order, VoterUserInterface $user): bool
    {
        return $order->getBuyer() !== null && $order->getBuyer()->getId() === $user->getId();
    }

    private function canView(Order $order, VoterUserInterface $user): bool
    {
        if ($order->getSeller() !== null && $order->getSeller()->getId() === $user->getId()) {
            return true;
        }
        if ($order->getBuyer() !== null && $order->getBuyer()->getId() === $user->getId()) {
            return true;
        }
        return false;
    }

    private function canSetBuyer(Order $order, VoterUserInterface $user): bool
    {
        return $order->getBuyer() === null;
    }


    private function canSetSeller(Order $order, VoterUserInterface $user): bool
    {
        return $order->getSeller() === null;
    }
}
