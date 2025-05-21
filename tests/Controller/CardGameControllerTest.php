<?php

namespace App\Tests\Controller;

use App\Card\CardsDeck;
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

    // public function testCardDeckDrawFive(): void
    // {
    //     $client = static::createClient();
    //     $session = self::getContainer()->get('session.factory')->createSession();

    //     $deck = new CardsDeck();    
    //     $deck->fillDeck();
    //     $deck->shuffle();
    //     var_dump($deck);
    
    //     $session->set('current_deck', $deck);
    //     var_dump($session->get('current_deck'));
    //     $session->set('removed_cards', []);
    //     $session->save();

    //     $client->request('GET', '/card/deck/draw/5');

    //     self::assertResponseIsSuccessful();
    // }
}
