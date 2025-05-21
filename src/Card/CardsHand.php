<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardsDeck;

/**
 * Class for a hand holding cards of class <CardGraphic>.
 */
class CardsHand
{
    /**
     * The Hand
     * @var array<CardGraphic>
     */
    private $hand;

    public function __construct()
    {
        $this->hand = [];
    }

    /**
     * Draws a card from argumented deck amd adds it to hand.
     * @param \App\Card\CardsDeck $deck Deck to draw card from.
     * @return void
     */
    public function drawCardToHand(CardsDeck $deck): void
    {
        $card = $deck->drawCard();
        // Returns an array (for flexibility in draw-ammount) of one card. Make new methods for readabillity?
        $this->add($card[0]);
    }

    /**
     * Add a card object to the hand.
     * @param \App\Card\CardGraphic $card
     * @return void
     */
    public function add(CardGraphic $card): void
    {
        array_push($this->hand, $card);
    }

    /**
     * Calls calculate value and returns the hands total value. Ace is 14.
     * @return int
     */
    public function getHandValue(): int
    {
        $ranks = [];

        foreach ($this->hand as $card) {
            array_push($ranks, $card->getValue()["rank"]);
        }

        return $this->calculateValue($ranks);
    }
    /**
     * Calculates combined value of cards in hand
     * @param array<string> $ranks Cards ranks: "5"=5, "Q"=12, "K"=13 etc.
     * @return int
     */
    private function calculateValue(array $ranks): int
    {
        $calculatedValue = 0;
        $valueTranslator = [
            "J" => 11,
            "Q" => 12,
            "K" => 13,
            "A" => 14
        ];

        foreach ($ranks as $rank) {
            if (is_numeric($rank)) {
                $calculatedValue += intval($rank);
                continue;
            }
            $calculatedValue += $valueTranslator[$rank];
        }

        return $calculatedValue;
    }

    /**
     * Method to check if hand has an ace.
     * @return int
     */
    public function holdsAces(): int
    {
        $aces = 0;

        foreach ($this->hand as $card) {
            if ($card->getValue()["rank"] === "A") {
                $aces += 1;
            }
        }

        return $aces;
    }


    /**
     * Gets hand as card symbols
     * @return array<string>
     */
    public function getCardsFromHand(): array
    {
        $cards = [];
        foreach ($this->hand as $card) {
            array_push($cards, $card->getAsCard());
        }
        return $cards;
    }
}
