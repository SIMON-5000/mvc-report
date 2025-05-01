<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardsDeck;

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

    public function drawCardToHand(CardsDeck $deck): void
    {
        $card = $deck->drawCard();
        // Returns an array (for flexibility in draw-ammount) of one card. Make new methods for readabillity?
        $this->add($card[0]);
    }

    private function add(CardGraphic $card): void
    {
        array_push($this->hand, $card);
    }

    public function getHandValue(): int
    {
        $score = 0;
        $ranks = [];

        foreach ($this->hand as $card) {
            array_push($ranks, $card->getValue()["rank"]);
        }

        $score = $this->calculateValue($ranks);

        return $score;
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
