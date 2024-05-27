<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ConferenceVoter extends Voter
{
    public const WASHIHI = 'WASHIHI';
    public const VIEW = 'CONFERENCE_VIEW';

    protected function supports(string $attribute, mixed $conference): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::WASHIHI, self::VIEW])
            && $conference instanceof \App\Entity\Conference;
    }

    protected function voteOnAttribute(string $attribute, mixed $conference, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::WASHIHI:
                // logic to determine if the user can EDIT
                // return true or false
                return $user == $conference->getUser();

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return  $user == $conference->getUser();
        }

        return false;
    }
}
