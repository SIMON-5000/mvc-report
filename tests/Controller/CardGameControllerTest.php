<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Testing what effect these low effort tests has.
 */
final class CardGameControllerTest extends WebTestCase
{
    public function testCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');

        self::assertResponseIsSuccessful();
    }

    public function testCardDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');

        self::assertResponseIsSuccessful();
    }

    public function testCardDeckShuffle(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');

        self::assertResponseIsSuccessful();
    }

    public function testCardDeckDraw(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/draw');

        self::assertResponseIsSuccessful();
    }

    public function testCardDeckDrawFive(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/draw/5');

        self::assertResponseIsSuccessful();
    }
}
