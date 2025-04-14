<?php
namespace App\Card;

use App\Card\CardGraphic;

class CardsDeck
{
    // heart, diamonds, clubs and spade:
    private $suits = ['spades', 'hearts', 'diamonds', 'clubs'];
    private $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
    private $deck = [];

    public function __construct()
    {
        foreach ($this->suits as $suit) {
            foreach ($this->ranks as $rank){
                $card = new CardGraphic($suit, $rank);
                $this->deck[] = $card;
                // var_dump($this->deck);
            }
        }
    }

    public function add(Card $card): void
    {
        $this->deck[] = $card;
    }

    public function shuffle(): void
    {
        shuffle($this->deck);
    }

    public function deckSize(): int
    {
        return count($this->deck);
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    public function getCardsDeck(): array
    {
        $cards = [];
        foreach ($this->deck as $card) {
            $cards[] = $card->getAsCard();
        }
        return $cards;
    }
}