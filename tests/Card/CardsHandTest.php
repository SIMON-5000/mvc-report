<?php

namespace App\Card;

use App\Card\CardsDeck;
use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardsHand.
 */
class CardsHandTest extends TestCase
{
    /**
     * Construct object and verify that the object can draw a card.
     */
    public function testCreateHand(): void
    {
        $hand = new CardsHand();
        $this->assertInstanceOf("\App\Card\CardsHand", $hand);

        $deck = new CardsDeck();
        $deck->fillDeck();

        $hand->drawCardToHand($deck);
        $this->assertNotEmpty($hand->getCardsFromHand());
    }

    /**
     * Assert getHandValue returns right number.
     * @return void
     */
    public function testGetHandValue(): void
    {
        $hand = new CardsHand();
        $deck = new CardsDeck();
        // Create a deck with one card: 5 and Jack of hearts. 5 + 11 = 16.
        $deck->createFromArray([["rank" => "5", "suit" => "hearts"], ["rank" => "J", "suit" => "hearts"]]);

        $hand->drawCardToHand($deck);
        $hand->drawCardToHand($deck);
        $res = $hand->getHandValue();
        $this->assertEquals(16, $res);
    }

    /**
     * Assert getHandValue returns right number.
     * @return void
     */
    public function testHoldsAces(): void
    {
        $hand = new CardsHand();
        $deck = new CardsDeck();
        $deck->createFromArray([["rank" => "A", "suit" => "hearts"]]);

        $hand->drawCardToHand($deck);
        $res = $hand->holdsAces();
        $this->assertEquals(1, $res);
    }


}
