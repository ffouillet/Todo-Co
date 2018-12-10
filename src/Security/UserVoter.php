<?php

// src/Security/PostVoter.php
namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    const LIST_VIEW = 'list_views';
    const ADD = 'add';
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::LIST_VIEW, self::ADD, self::EDIT, self::DELETE))) {
            return false;
        }

        // only vote on User objects inside this voter
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::LIST_VIEW:
            case self::ADD:
            case self::DELETE:
            case self::EDIT:
                return $authenticatedUser->hasRole('ROLE_ADMIN');
        }

        throw new \LogicException('This code should not be reached!');
    }
}