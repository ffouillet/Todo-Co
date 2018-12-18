<?php

namespace App\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    protected $clientAnonymous;
    protected $clientUser;
    protected $clientAdmin;

    public function setUp() {

        // Easy login thanks to LiipFunctionalTestBundle.
        $adminCredentials = array(
            'username' => 'testAdmin',
            'password' => 'testPassword'
        );

        $userCredentials = array(
            'username' => 'testUser',
            'password' => 'testPassword'
        );

        $this->clientUser = $this->makeClient($userCredentials);
        $this->clientAdmin = $this->makeClient($adminCredentials);
        $this->clientAnonymous = static::createClient();
    }

    public function testUserListWithUser() {
        $this->clientUser->request('GET', '/users');

        $this->assertEquals(403, $this->clientUser->getResponse()->getStatusCode());
    }

    public function testUserListWithAnonymousUser() {
        $this->clientAnonymous->request('GET', '/users');

        $this->assertEquals(302, $this->clientAnonymous->getResponse()->getStatusCode());

        $crawler = $this->clientAnonymous->followRedirect();

        $this->assertEquals(1, $crawler->filter('html:contains("Connexion")')->count());
    }

    public function testUserListWithAdmin() {
        $crawler = $this->clientAdmin->request('GET', '/users');

        $this->assertEquals(200, $this->clientAdmin->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("Liste des utilisateurs")')->count());
    }

    public function testCreateWithAnonymousUser() {
        $this->clientAnonymous->request('GET', '/users/create');

        $this->assertEquals(302, $this->clientAnonymous->getResponse()->getStatusCode());

        $crawler = $this->clientAnonymous->followRedirect();

        $this->assertEquals(1, $crawler->filter('html:contains("Connexion")')->count());
    }

    public function testCreateWithUser() {
        $this->clientUser->request('GET', '/users/create');

        $this->assertEquals(403, $this->clientUser->getResponse()->getStatusCode());
    }

    public function testCreateWithAdmin() {
        $crawler = $this->clientAdmin->request('GET', '/users/create');

        $this->assertEquals(200, $this->clientAdmin->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = 'testCreatedUser';
        $form['user[password][first]'] = 'testPassword';
        $form['user[password][second]'] = 'testPassword';
        $form['user[email]'] = 'defaultMail@functional-tests.com';
        $form['user[roles]']->select('ROLE_USER');

        $this->clientAdmin->submit($form);

        $this->assertSame(302, $this->clientAdmin->getResponse()->getStatusCode());

        $crawler = $this->clientAdmin->followRedirect();

        $this->assertEquals(1, $crawler->filter('td:contains("testCreatedUser")')->count());
    }

    public function testEditWithAnonymousUser() {
        $this->clientAnonymous->request('GET', '/users/3/edit');

        $this->assertEquals(302, $this->clientAnonymous->getResponse()->getStatusCode());

        $crawler = $this->clientAnonymous->followRedirect();

        $this->assertEquals(1, $crawler->filter('html:contains("Connexion")')->count());
    }

    public function testEditWithUser() {
        $this->clientUser->request('GET', '/users/3/edit');

        $this->assertEquals(403, $this->clientUser->getResponse()->getStatusCode());
    }

    public function testEditWithAdmin() {
        $crawler = $this->clientAdmin->request('GET', '/users/3/edit');

        $this->assertEquals(200, $this->clientAdmin->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Modifier')->form();

        $form['user[username]'] = 'testEditedCreatedUser';
        $form['user[password][first]'] = 'testEditPassword';
        $form['user[password][second]'] = 'testEditPassword';
        $form['user[email]'] = 'defaultMailEdited@functional-tests.com';
        $form['user[roles]']->select('ROLE_ADMIN');

        $this->clientAdmin->submit($form);

        $this->assertSame(302, $this->clientAdmin->getResponse()->getStatusCode());

        $crawler = $this->clientAdmin->followRedirect();

        $this->assertEquals(1, $crawler->filter('td:contains("testEditedCreatedUser")')->count());
    }


}