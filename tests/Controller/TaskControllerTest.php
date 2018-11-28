<?php

namespace App\Tests\Controller;

use App\Tests\RequiredAuthenticationWebTestCase;

class TaskControllerTest extends RequiredAuthenticationWebTestCase
{

    public function testListTodo()
    {
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h1:contains("Liste des tâches à effectuer")')->count());
    }

    public function testListCompleted()
    {
        $crawler = $this->client->request('GET', '/tasks/completed');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('h1:contains("Liste des tâches effectuées")')->count());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/tasks/create');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Get the form
        $formButtonCrawlerNode = $crawler->selectButton('task_submit');
        $form = $formButtonCrawlerNode->form();


        $form['task[title]'] = 'Functional Test Task';
        $form['task[content]'] = 'This task gets created for functional/units tests of this project.';

        // Submit the form with values

        //dump($this->client->getContainer()->get('session'));
        $crawler = $this->client->submit($form);

        // Check if redirection occurs.
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        //$this->assertEquals(1, $crawler->filter('h1:contains("Liste des tâches effectuées")')->count());
    }
}