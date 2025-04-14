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
    protected $suit;
    protected $rank;

    public function __construct(string $suit, string $rank)
    {
        $this->suit = $suit;
        $this->rank = $rank;
    }

    public function getValue(): Array
    {
        return ["suit" => $this->suit, "rank" => $this->rank];
    }
}
