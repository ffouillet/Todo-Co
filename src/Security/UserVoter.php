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

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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

        $user = $subject;

        switch ($attribute) {
            case self::LIST_VIEW:
            case self::ADD:
            case self::DELETE:
                return $this->isAdmin();
            case self::EDIT:
                return $this->canEdit($user, $authenticatedUser);

        }

        throw new \LogicException('This code should not be reached!');
    }

    // Only users with ROLE_ADMIN can add or delete other users.
    public function isAdmin() {

        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    // User can only edit his own profile.
    // Users with ROLE_ADMIN can also edit all profiles.
    public function canEdit(User $user, User $authenticatedUser) {

        if($user->getId() == $authenticatedUser->getId() || $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

}