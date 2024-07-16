<?php

namespace App\Tests\FunctionnalTest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceTest extends WebTestCase
{
    public function testConference(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Hello World') ;
        $this->assertSelectorTextContains('a', 'Accueil');
    } 
}
