<?php

namespace App\Tests;

use App\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;
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

        $this->login();
    }

    protected function logIn(){

        // There is certainly a way to improve that...
        $this->loadFixtures(array('App\DataFixtures\UserFixtures'));

        // Easy login thanks to LiipFunctionalTestBundle.
        $credentials = array(
            'username' => 'testAdmin',
            'password' => 'testPassword'
        );

        $this->client = $this->makeClient($credentials);
    }
}
