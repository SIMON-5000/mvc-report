<?php
namespace App\Card;

use App\Card\CardGraphic;

class CardsDeck
{
    // heart, diamonds, clubs and spade:
    private $suits = ['spades', 'hearts', 'diamonds', 'clubs'];
    private $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
    private $deck;

    public function __construct()
    {
        $this->deck = [];
    }

    public function fillDeck(): void
    {
        foreach ($this->suits as $suit) {
            foreach ($this->ranks as $rank){
                $card = new CardGraphic($suit, $rank);
                $this->add($card);
                // var_dump($this->deck);
            }
        }
    }

    public function createFromArray($cardsList): void
    {
        $this->deck = [];
        foreach ($cardsList as $card) {
            $this->add(new CardGraphic($card['suit'], $card['rank']));
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

    public function draw($number = 1): array
    {
        $drawn = [];

        for ($count = 0; $count < $number; $count++) {
            $idx = random_int(0, $this->deckSize() - 1);
            $removedCard = array_splice($this->deck, $idx, 1);
            $drawn += $removedCard[0]->getValue();
        }

        return $drawn;
    }


    // public function saveDeck(): array
    // {
    //     $currentDeck = [];

    //     foreach ($this->deck as $card) {
    //         $currentDeck[] = $card->getValue();
    //     }

    //     return $currentDeck;
    // }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }

    public function getCardsFromDeck(): array
    {
        $cards = [];
        foreach ($this->deck as $card) {
            $cards[] = $card->getAsCard();
        }
        return $cards;
    }
}