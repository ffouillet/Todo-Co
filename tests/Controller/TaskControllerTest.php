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

        // Fill the form
        $form['task[title]'] = 'Functional Test Task';
        $form['task[content]'] = 'This task gets created for functional/units tests of this project.';

        $this->client->submit($form);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('td:contains("Functional Test Task")')->count());
    }

    public function testEdit()
    {
        // We'll use previously created task.
        $crawler = $this->client->request('GET', '/tasks/1/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Get the form
        $formButtonCrawlerNode = $crawler->selectButton('task_edit');
        $form = $formButtonCrawlerNode->form();

        // Fill the form
        $form['task_edit[title]'] = 'Functional Edited Test Task';
        $form['task_edit[content]'] = 'This task gets edited for functional/units tests of this project.';

        $this->client->submit($form);

        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();

        $this->assertEquals(1, $crawler->filter('td:contains("Functional Edited Test Task")')->count());

    }

    public function testEditNotByOwner()
    {
        // We'll login temporary with a different user this time.
        $credentials = array(
            'username' => 'testUser',
            'password' => 'testPassword'
        );

        $otherClient = $this->makeClient($credentials);

        // This task has been created by user 'testAdmin'
        $crawler = $otherClient->request('GET', '/tasks/1/edit');

        $this->assertEquals(200, $otherClient->getResponse()->getStatusCode());

        // Get the form
        $formButtonCrawlerNode = $crawler->selectButton('task_edit');
        $form = $formButtonCrawlerNode->form();

        // Fill the form
        $form['task_edit[title]'] = 'Functional Edited Test Task That Won\'t Work';
        $form['task_edit[content]'] = 'This task gets edited and will fail sfor functional/units tests of this project.';

        $otherClient->submit($form);

        $this->assertSame(403, $otherClient->getResponse()->getStatusCode());

    }

    public function testToggle()
    {

        $this->client->request('GET', '/tasks/1/toggle');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // We'll now check that the task is visible in the completed task table.
        $crawler = $this->client->request('GET', '/tasks/completed');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('td:contains("Functional Edited Test Task")')->count());

    }

    public function testDelete() {

        $this->client->request('GET', '/tasks/1/delete');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // We'll now check that the task has really been deleted
        $this->client->request('GET', '/tasks/1/edit');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}