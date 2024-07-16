<?php

namespace App\Tests\FunctionnalTest;

use App\Entity\User;
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
        $form['conference[categorie]'] = 4;
        $form['conference[competence][2]'] = 8;
        $client->submit($form);
        $this->assertResponseIsSuccessful();
    }
    public function testReadConference()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $conferences = $manager->getRepository(Conference::class)->findAll();

        $this->assertNotEmpty($conferences);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('conference.index');
    }

    public function testUpdateConference()
    {
        // on se connecte sur le site
        // $this->logIn();
        // debut connexion
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'gjenkins@gmail.com';
        $form['password'] = 'blablabla';
        $client->submit($form);
        // fin connexion 
        // $this->assertResponseIsSuccessful();


        $router = $this->getContainer()->get('router');
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = $manager->getRepository(User::class)->findAll();
        $conference = $manager->getRepository(Conference::class)->findOneBy(['user' => $user[0]]);

// dd($conference);
        $conferences = $manager->getRepository(Conference::class)->findAll();
        $this->assertIsArray($conferences);
        $crawler = $client->request('GET', $router->generate('conference.edit', ['id' => $conference->getId()]));
        // ex: /conference/edit/11
        // dd($crawler);
        $crawler->filter("form[name=conference]")->form(
            [
                "conference[titre]" => 'teste du titre',
                'conference[description]' => 'teste de la description'
            ]
        );
        $client->submit($form);
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    // public function logIn()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/login');
    //     $form = $crawler->selectButton('Se connecter')->form();
    //     $form['email'] = 'gjenkins@gmail.com';
    //     $form['password'] = 'blablabla';
    //     $client->submit($form);
    //     $this->assertResponseIsSuccessful();
    // }
}
