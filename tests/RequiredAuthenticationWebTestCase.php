<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * RequiredAuthenticationWebTestCase allows to simulate an user authentication so that the client
 * can access application's secured areas (depending on the user role).
 */
abstract class RequiredAuthenticationWebTestCase extends WebTestCase
{
    protected $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->client = static::createClient();

        $this->login($this->client, 'main', 'main', ['ROLE_ADMIN']);
    }

    protected function logIn(Client $client, $firewallName, $firewallContext, $userRoles = ['ROLE_USER']){

        $session = $client->getContainer()->get('session');

        // Create a user
        $user = $this->getMockBuilder('App\Entity\User')->getMock();
        $user->method('getId')->willReturn(1);
        $user->method('getUsername')->willReturn("testUser");
        $user->method('getPassword')->willReturn("testPassword");

        // This project use a Guard Authentication, the authenticated token is a PostAuthenticationGuardToken.
        $token = new PostAuthenticationGuardToken($user, $firewallName, $userRoles);

        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $client->getCookieJar()->set($cookie);
    }
}
