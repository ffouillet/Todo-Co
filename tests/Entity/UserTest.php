<?php
namespace App\Tests\Entity;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testAttributes()
    {
        $taskStub = $this->createMock(Task::class);

        $taskStub->method('getId')
            ->willReturn(0);

        $user = new User();
        $user->setUsername('testUserName');
        $user->setPassword('encodedPassword');
        $user->setEmail('user@entity-unit-test.com');
        $user->setRoles(['ROLE_USER']);

        static::assertNull($user->getId());
        static::assertEquals('testUserName', $user->getUsername());
        static::assertEquals('encodedPassword', $user->getPassword());
        static::assertEquals('user@entity-unit-test.com', $user->getEmail());
        static::assertEquals(['ROLE_USER'], $user->getRoles());

    }
}