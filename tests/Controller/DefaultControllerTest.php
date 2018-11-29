<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    private $client;

    public function setUp() {
        $this->client = static::createClient();
    }

    public function testIndexNotAuthenticated()
    {

        $this->client->request('GET', '/');

        $crawler = $this->client->followRedirect();

        // Ensure we are on login page
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Connexion")')->count());

    }
}