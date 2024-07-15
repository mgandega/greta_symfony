<?php

namespace App\Tests\UnitTest;

use App\Entity\Conference;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConferenceTest extends KernelTestCase
{
    public function testEntity(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testGetEntity()
    {
        $manager = static::getContainer()->get('doctrine.orm.entity_manager');
        $conferences = $manager->getRepository(Conference::class)->findAll();
        $this->assertNotEmpty($conferences);
        $this->assertCount(10, $conferences);
    }
}
