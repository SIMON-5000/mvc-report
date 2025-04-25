<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardsDeck
{
    /**
     * The classic suits of cards.
     * @var array<string>
     */
    private $suits = ['spades', 'hearts', 'diamonds', 'clubs'];
    /**
     * Ranks from ace to king.
     * @var array<string>
     */
    private $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

    /**
     * The deck
     * @var array<CardGraphic>
     */
    private $deck;

    public function __construct()
    {
        $this->deck = [];
    }

    public function fillDeck(): void
    {
        foreach ($this->suits as $suit) {
            foreach ($this->ranks as $rank) {
                $card = new CardGraphic($suit, $rank);
                $this->add($card);
                // var_dump($this->deck);
            }
        }
    }

    /**
     * Creates cards from an array.
     * @param array<array{suit: string, rank: string}> $cardsList
     * @return void
     */
    public function createFromArray(array $cardsList): void
    {
        $this->deck = [];
        foreach ($cardsList as $card) {
            $this->add(new CardGraphic($card['suit'], $card['rank']));
        }
    }

    public function add(CardGraphic $card): void
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

    /**
     * Draws one or more cards.
     * @param int $number
     * @return list<array{rank: string, suit: string}>
     */
    public function draw(int $number = 1): array
    {
        $drawn = [];

        for ($count = 0; $count < $number; $count++) {
            $idx = random_int(0, $this->deckSize() - 1);
            $removedCard = array_splice($this->deck, $idx, 1);
            array_push($drawn, $removedCard[0]->getValue());
        }

        return $drawn;
    }

    /**
     * Draws one or more cards.
     * @param int $number
     * @return array<CardGraphic>
     */
    public function drawCard(int $number = 1): array
    {
        $drawn = [];

        for ($count = 0; $count < $number; $count++) {
            $idx = random_int(0, $this->deckSize() - 1);
            $removedCard = array_splice($this->deck, $idx, 1);
            array_push($drawn, $removedCard[0]);
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

    /**
     * Gets deck as string values
     * @return list<array<string, string>>
     */
    public function getValues(): array
    {
        $values = [];
        foreach ($this->deck as $card) {
            $values[] = $card->getValue();
        }
        return $values;
    }
    
    /**
     * Gets deck as card symbols
     * @return array<string>
     */
    public function getCardsFromDeck(): array
    {
        $cards = [];
        foreach ($this->deck as $card) {
            $cards[] = $card->getAsCard();
        }
        return $cards;
    }
}
