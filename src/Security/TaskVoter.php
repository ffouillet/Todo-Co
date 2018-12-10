<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::EDIT, self::DELETE))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Task) {
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

        // you know $subject is a Task object, thanks to supports
        /** @var Task $task */
        $task = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($task, $authenticatedUser);
            case self::DELETE:
                return $this->canDelete($task, $authenticatedUser);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Task $task, User $authenticatedUser)
    {
        // Task should be delete only by their authors.
        // Else if task has no author, task should only be deletable by an user with ROLE_ADMIN
        if($task->getAuthor() !== null && ($task->getAuthor()->getId() == $authenticatedUser->getId()) ||
            ($task->getAuthor() == null && $authenticatedUser->hasRole('ROLE_ADMIN'))) {
                return true;
        }

        return false;
    }

    private function canEdit(Task $task, User $authenticatedUser) {
        // Tasks are editable only by their authors or user with ROLE_ADMIN
        if($task->getAuthor()->getId() == $authenticatedUser->getId() ||
            $authenticatedUser->hasRole('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

}