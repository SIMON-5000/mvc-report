<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardsDeck.
 */
class CardsDeckTest extends TestCase
{
    /**
     * Construct object and verify that the object can be filled.
     * Is it OK to implicitly test things?
     */
    public function testFillHand(): void
    {
        $deck = new CardsDeck();
        $this->assertInstanceOf("\App\Card\CardsDeck", $deck);

        $deck->fillDeck();
        $this->assertNotEmpty($deck->getCardsFromDeck());
    }

    /**
     * Testing Draw method.
     * Expects to return an array of Card values.
     * @return void
     */
    public function testDrawCard(): void
    {
        $deck = new CardsDeck();
        // $deck->fillDeck();
        $deck->createFromArray([["suit" => "clubs", "rank" => "2"]]);

        // Array destructuring, does this work? drawCard returns an array of assoc. arrays
        [$cardArray] = $deck->draw();
        $this->assertEquals("2", $cardArray["rank"]);
    }


    /**
     * Construct object and verify that the deck can be shuffled.
     */
    public function testShuffle(): void
    {
        $deck = new CardsDeck();
        $deck->fillDeck();

        $deckState1 = $deck->getValues();
        $deck->shuffle();
        $deckState2 = $deck->getValues();

        $this->assertNotEquals($deckState1, $deckState2);
    }
}
