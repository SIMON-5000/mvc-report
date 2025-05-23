<?php

namespace App\Card;

use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Bundle\MakerBundle\Str;

/**
 * Inherits the Card class
 */
class CardGraphic extends Card
{
    /**
     * Summary of suits
     * A B C D E Represents ranks: '10' 'Jack' 'Knight' 'Queen' 'King'. I will not be using Knight
     * https://www.compart.com/en/unicode/block/U+1F0A0
     * @var array<string> The four suits.
     */
    private $suits = [
        'spades' => 'A',
        'hearts' => 'B',
        'diamonds' => 'C',
        'clubs' => 'D'
    ];

    /**
     * Card ranks from Ace to King.
     * @var array<string>
     */
    private $ranks =
        ['A' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => 'A',
        'J' => 'B',
        'Q' => 'D',
        'K' => 'E'
        ];


    /**
     * Calls inherited constructor.
     * @param string $suit Cards suit.
     * @param string $rank Cards rank.
     */
    public function __construct(string $suit, string $rank)
    {
        parent::__construct($suit, $rank);
    }


    /**
     * Concatenates a string with the Unicode-value.
     * Uses mb_chr https://www.php.net/manual/en/function.mb-chr.php
     * mb_char takes a "codepoint" as argument, it works if I convert my string to type hexadecimal.
     * https://stackoverflow.com/questions/1365583/how-to-get-the-character-from-unicode-code-point-in-php
     *
     * @return string Returns a graphic representation of a playing card.
     */
    public function getAsCard(): string
    {
        $unicodeCard = "U+1F0" . $this->suits[$this->suit] . $this->ranks[$this->rank];
        // Wrapped hexdec in intval, to ensure phpstan it is a int.
        return mb_chr(intval(hexdec($unicodeCard)), 'UTF-8');
    }
}
