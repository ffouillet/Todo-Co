<?php
namespace App\Tests\AppBundle\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testAttributes()
    {
        $now = new \DateTime('NOW');

        $userStub = $this->createMock(User::class);
        $userStub->method('getId')
            ->willReturn(0);

        $task = new Task();
        $task->setTitle('Unit Tested Task');
        $task->setContent('Unit Tested Task content');
        $task->setCreatedAt($now);
        $task->toggle(true);
        $task->setAuthor($userStub);

        static::assertNull($task->getId());
        static::assertEquals('Unit Tested Task', $task->getTitle());
        static::assertEquals('Unit Tested Task content', $task->getContent());
        static::assertEquals($now, $task->getCreatedAt());
        static::assertEquals(true, $task->isDone());
        static::assertEquals(0, $task->getAuthor()->getId());
    }
}