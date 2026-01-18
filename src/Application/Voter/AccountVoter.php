<?php

namespace App\Application\Voter;

use App\Domain\Entity\Account;
use App\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccountVoter extends Voter
{
    public const VIEW = 'account_view';
    public const EDIT = 'account_edit';
    public function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Account) {
            return false;
        }
        return in_array($attribute, [self::VIEW, self::EDIT]);
    }

    public function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof VoterUserInterface) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles()))
        {
            return true;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::VIEW => $this->canViev($subject, $user),
            default => false,
        };
    }

    private function canEdit(Account $account, VoterUserInterface $user): bool
    {
        return $account->getUser() && $account->getUser()->getId() === $user->getId();
    }

    private function canViev(Account $account, VoterUserInterface $user): bool
    {
        return $account->getUser() && $account->getUser()->getId() === $user->getId();
    }
}
