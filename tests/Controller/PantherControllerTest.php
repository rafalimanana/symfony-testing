<?php

namespace App\Tests\Controller;

use Symfony\Component\Panther\PantherTestCase;

class PantherControllerTest extends PantherTestCase
{

    public function testTextPagePanther()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/contact');
        $this->assertSelectorTextContains('h1', 'Nous contacter');
    }

    public function testSendInvalidName()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('envoyer')->form([
            'name'=>'',
            'email'=>'test@gmail.com',
            'subject'=>'Test send',
            'message'=>'Bonjour',
            'phone'=>'0341892188',
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSendInvalidEmail()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('envoyer')->form([
            'name'=>'Test',
            'email'=>'test',
            'subject'=>'Test send',
            'message'=>'Bonjour',
            'phone'=>'0341892188',
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.alert-danger>span', 'This value is not a valid email address.');
    }

    public function testSendInvalidPhone()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('envoyer')->form([
            'name'=>'Test',
            'email'=>'test@gmail.com',
            'subject'=>'Test send',
            'message'=>'Bonjour',
            'phone'=>'',
        ]);
        $client->submit($form);
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testSendInvalidSubject()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('envoyer')->form([
            'name'=>'Test',
            'email'=>'test@gmail.com',
            'subject'=>'',
            'message'=>'Bonjour',
            'phone'=>'0341892188',
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.alert-danger>span', 'The field must not be empty');
    }

    public function testSendSuccess()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/contact');
        $form = $crawler->selectButton('envoyer')->form([
            'name'=>'Test',
            'email'=>'test@gmail.com',
            'subject'=>'Test send',
            'message'=>'Bonjour',
            'phone'=>'0341892188',
        ]);
        $client->submit($form);
        $this->assertSelectorNotExists('.alert.alert-danger');
    }
}
