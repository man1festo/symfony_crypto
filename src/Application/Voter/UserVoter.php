<?php

namespace App\Application\Voter;

use App\Domain\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const VIEW = 'user_view';
    public const EDIT = 'user_edit';

    public function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof User)
        {
            return false;
        }
        return in_array($attribute, [self::VIEW, self::EDIT], true);
    }

    public function voteOnAttribute(string $attribute, mixed $subject, mixed $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof VoterUserInterface)
        {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles()))
        {
            return true;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($user, $subject),
            self::EDIT => $this->canEdit($user, $subject),
            default => false,
        };
    }

    private function canEdit(VoterUserInterface $user, mixed $subject): bool
    {
        return $user->getId() === $subject->getId();
    }

    private function canView(VoterUserInterface $user, mixed $subject): bool
    {
        return $user->getId() === $subject->getId();
    }
}
