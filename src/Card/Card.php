<?php
namespace App\Card;

use Symfony\Bundle\MakerBundle\Str;

/**
 * A card for a standard 52-card deck,
 * available in 13 ranks in each of the
 * four suits: clubs (♣), diamonds (♦), hearts (♥) and spades (♠).
 * https://en.wikipedia.org/wiki/Standard_52-card_deck
 */
class Card
{
    // private $suits = ['h', 'd', 'c', 's'];
    // private $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
    protected $suit;
    protected $rank;

    public function __construct(string $suit, string $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    public function getValue(): string
    {
        return "$this->suit . $this->rank";
    }
}
