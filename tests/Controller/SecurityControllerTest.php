<?php

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{

    protected $client;

    public function setUp() {

        $this->client = static::createClient();

        // Manually login user.
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertEquals(1, $crawler->filter('h1:contains("Connexion")')->count());

        $form = $crawler->selectButton('Connexion')->form();

        $form['username'] = 'testUser';
        $form['password'] = 'testPassword';

        $this->client->submit($form);
    }

    public function testLogin()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('html:contains("Utilisateur connecté: testUser")')->count());

    }

    public function testLogout()
    {
        // User will be sent to homepage after logout.
        // Because he is not logged out anymore and homepage require ROLE_USER, he will be redirected once again to login page
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/logout');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h1:contains("Connexion")')->count());
    }

}