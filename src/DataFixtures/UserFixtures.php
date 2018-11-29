<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            ['testUser', 'testPassword', 'testUser@functional-tests.test', ['ROLE_USER']],
            ['testAdmin', 'testPassword', 'testAdmin@functional-tests.test', ['ROLE_ADMIN']]
        ];

        foreach($users as $userInfos) {
            $user = new User();
            $user->setUsername($userInfos[0]);
            $plainPassword = $userInfos[1];
            $user->setPassword($this->encoder->encodePassword($user, $plainPassword));
            $user->setEmail($userInfos[2]);
            $user->setRoles($userInfos[3]);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
