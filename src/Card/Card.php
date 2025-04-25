<?php

namespace App\Card;

use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Bundle\MakerBundle\Str;

/**
 * A card for a standard 52-card deck,
 * available in 13 ranks in each of the
 * four suits: clubs (♣), diamonds (♦), hearts (♥) and spades (♠).
 * https://en.wikipedia.org/wiki/Standard_52-card_deck
 */
class Card
{
    protected string $suit;
    protected string $rank;

    public function __construct(string $suit, string $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }
    /**
     * Returns associative array of cards string representation.
     * @return array{rank: string, suit: string}
     */
    public function getValue(): array
    {
        return [
            "suit" => $this->suit,
            "rank" => $this->rank
        ];
    }
}
