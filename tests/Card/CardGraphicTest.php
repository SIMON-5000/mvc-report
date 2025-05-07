<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Tests CardGraphic class
 */
class CardGraphicTest extends TestCase
{
    /**
     * This class gets 100% cover from higher level classes,
     * but should maybe be tested explixcitly?
     */
    /**
     * Construct object and verify that the object can draw a card.
     */
    public function testCreateCard(): void
    {
        $card = new CardGraphic("hearts", "2");
        $this->assertInstanceOf("\App\Card\CardGraphic", $card);

        $res= $card->getAsCard();
        $this->assertEquals("🂲", $res);
    }

}