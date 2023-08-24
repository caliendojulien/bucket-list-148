<?php

namespace App\Tests\Controller;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WishControllerTest extends WebTestCase
{

    public function routes(): Generator
    {
        yield "Page d'accueil" => ["GET", "/"];
        yield "Pages des wishes" => ["GET", "/wishes/"];
    }

    /**
     * @dataProvider routes
     */
    public function testRoutes(
        string $methode,
        string $url
    ): void
    {
        $client = static::createClient();
        $client->request($methode, $url);
        $this->assertResponseIsSuccessful();
    }

    public function testWishes(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/wishes/');

        $this->assertResponseIsSuccessful();
        $liens = $crawler->filter('h1 > a');
        $this->assertEquals(2, $liens->count());
    }

}
