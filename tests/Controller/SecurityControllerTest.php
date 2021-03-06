<?php

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

class SecurityControllerTest extends WebTestCase
{

    protected $client;

    public function setUp() {

        $this->client = static::createClient();

    }

    public function testLoginInvalidCSRFToken(){
        // Manually login user.
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form();

        $form['username'] = "testUser";
        $form['password'] = "testPassword";
        $form['_csrf_token'] = "fakeToken";

        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->folloWRedirect();

        $this->assertEquals(1, $crawler->filter('html:contains("Jeton CSRF invalide.")')->count());

    }

    public function testLoginRedirectToTargetPath() {

        /**
         * User tries to access task creation. Will get redirected to login page
         * then if login was as success (thanks to target path stored in session,
         * will get redirected to task creation page
         **/
        $this->client->request('GET', '/tasks/create');

        $crawler = $this->client->followRedirect();

        $form = $crawler->selectButton('Connexion')->form();

        $form['username'] = "testUser";
        $form['password'] = "testPassword";

        $this->client->submit($form);

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

    }

    public function testLogin()
    {

        $this->manuallyLogUser($this->client, 'testUser', 'testPassword');

        $crawler = $this->client->request('GET', '/');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('html:contains("Utilisateur connecté: testUser")')->count());

    }

    public function testLoginWrongUsername() {

        $this->manuallyLogUser($this->client, 'wrongUsername', 'wrongPassword');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followredirect();

        $this->assertEquals(1, $crawler->filter('html:contains("Le nom d\'utilisateur n\'a pas pu être trouvé.")')->count());
    }

    public function testLoginWrongCredentials() {
        $this->manuallyLogUser($this->client, 'testUser', 'wrongPassword');

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followredirect();

        $this->assertEquals(1, $crawler->filter('html:contains("Identifiants invalides.")')->count());
    }

    public function testLogout()
    {
        $this->manuallyLogUser($this->client, 'testUser', 'testPassword');

        // User will be sent to homepage after logout.
        // Because he is not logged out anymore and homepage require ROLE_USER, he will be redirected once again to login page
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', '/logout');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h1:contains("Connexion")')->count());
    }

    public function manuallyLogUser(Client $client, $username, $password){
        // Manually login user.
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form();

        $form['username'] = $username;
        $form['password'] = $password;

        $client->submit($form);
    }

}