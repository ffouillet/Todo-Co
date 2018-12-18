<?php
namespace App\Tests\AppBundle\Entity;

use App\DataFixtures\TaskFixtures;
use App\Entity\User;
use App\Entity\Task;

class TaskConstraintsTest extends AbstractConstraintsTest
{

    private $task;
    private $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = new User();
        $this->user->setUsername('demoUser');
        $this->user->setPassword('encodedPassword');
        $this->user->setEmail('user@entity-unit-test.com');
        $this->user->setRoles(['ROLE_USER']);

        $this->task = new Task();
        $this->task->setTitle('Constraint test task');
        $this->task->setContent('This taks has been created only for testing its validation constraints');
        $this->task->setAuthor($this->user);
        $this->task->setCreatedAt(new \DateTime());

    }

    public function testEmptyTitleValidation()
    {
        $this->notBlankValidationConstraintTest($this->task, 'title');
    }

    public function testEmptyContentValidation()
    {
        $this->notBlankValidationConstraintTest($this->task, 'content');
    }


}