<?php

namespace App\Tests\FunctionnalTest;

use App\Entity\Conference;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConferenceCrudTest extends WebTestCase
{
    public function testAjoutConference(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/conference/add');

        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Ajouter une conférence')->form();
        $form['conference[titre]'] = 'titre de teste';
        $form['conference[description]'] = 'description de teste';
        $form['conference[lieu]'] = 'lieu de teste';
        $form['conference[categorie]'] = 2;
        $form['conference[competence][2]'] = 3;
        $client->submit($form);
        $this->assertResponseIsSuccessful();
    }
    public function testReadConference(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conferences = $manager->getRepository(Conference::class)->findAll();

        $this->assertNotEmpty($conferences);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('conference.index');
    }


}
